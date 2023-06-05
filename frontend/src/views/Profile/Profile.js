import React, {useEffect, useState} from 'react';
import style from './Profile.module.css';
import Page from '../../components/Page/Page';
import avatar from '../../images/bigAvatar.png';
import MovieList from "../../components/MovieList/MovieList";
import follow from "../../images/follow.svg";
import followFilled from "../../images/followFilled.svg";
import axios from "axios";
import {API_BASE_URL} from "../../index";
import {useNavigate, useParams} from 'react-router-dom';
import {useCookies} from "react-cookie";
import jwt_decode from "jwt-decode";

function Profile() {
    const navigate = useNavigate();
    const [user, setUser] = useState(null);
    const [heartIcon, setHeartIcon] = useState(follow);
    const [alreadyFollowed, setAlreadyFollowed] = useState(false);
    const [ratings, setRatings] = useState([]);
    const [favourites, setFavourites] = useState([]);
    const [wts, setWts] = useState([]);
    const [cookie] = useCookies(['jwt']);
    const {id} = useParams();
    const lgthRates = ratings.length;
    const lgthFav = favourites.length;
    const lgthWts = wts.length;

    useEffect(() => {
        const fetchUser = async () => {
            try {
                const response = await axios.get(`${API_BASE_URL}user/${id}`, {
                    headers: {
                        Authorization: `Bearer ${cookie.jwt}`,
                    }
                });
                setUser(response.data);
                setAlreadyFollowed(response.data.follows);
            } catch (error) {
                console.log(error);
                navigate('/');
            }
        };

        const fetchSpecials = async () => {
            try {
                const response = await axios.get(`${API_BASE_URL}getSpecials/${id}`);
                setRatings(response.data[0]);
                setFavourites(response.data[1]);
                setWts(response.data[2]);
            } catch (error) {
                console.log(error);
            }
        };
        if (!cookie.jwt) {
            navigate('/');
        } else {
            fetchUser();
            fetchSpecials();
        }
    }, [cookie.jwt, navigate, id]);

    useEffect(() => {
        if (user && user.email === jwt_decode(cookie.jwt).username) {
            setHeartIcon(followFilled);
        } else if (alreadyFollowed) {
            setHeartIcon(followFilled);
        } else {
            setHeartIcon(follow);
        }
    }, [user, alreadyFollowed, cookie.jwt]);

    const handleFollow = async () => {
        setAlreadyFollowed(!alreadyFollowed);
        try {
            const mode = alreadyFollowed ? 'unfollow' : 'follow';
            await axios.post(
                `${API_BASE_URL}${mode}/${id}`,
                {},
                {
                    headers: {
                        Authorization: `Bearer ${cookie.jwt}`,
                    }
                }
            );
        } catch (error) {
            console.log(error);
        }
    };

    return (
        <Page subpage="profile">
            <div className={style.profileDiv}>
                {user && (
                    <div className={style.user}>
                        <img className={style.bigAvatar} src={avatar} alt="avatar"/>
                        <p className={style.bigUsername}>{user.firstName} {user.lastName}</p>
                        <p className={style.email}>{user.email}</p>
                        {user.email !== jwt_decode(cookie.jwt).username && (
                            <img onClick={handleFollow} className={style.follow} src={heartIcon} alt="heart"/>
                        )}
                    </div>
                )}
                <div className={style.counted}>
                    Rated: <div className={style.number}>{lgthRates}</div>
                    Want to See: <div className={style.number}>{lgthWts}</div>
                    Favourite: <div className={style.number}>{lgthFav}</div>
                </div>
                <MovieList title="Your Best Rated Movies" data={ratings}/>
                <MovieList title="Movies You Want to See" data={wts}/>
                <MovieList title="Your Favourite Movies" data={favourites}/>
            </div>
        </Page>
    );
}

export default Profile;
