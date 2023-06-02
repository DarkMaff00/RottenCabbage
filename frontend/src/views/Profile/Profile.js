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
    const [email, setEmail] = useState('');
    const [names, setNames] = useState('');
    const [heartIcon, setHeartIcon] = useState(follow);
    const [enableFollow, setEnableFollow] = useState(true);
    const [alreadyFollowed, setAlreadyFollowed] = useState(false);
    const [cookie] = useCookies(['jwt']);
    const {id} = useParams();

    useEffect(() => {
        if (!cookie.jwt) {
            navigate('/');
        }
    }, [cookie.jwt, navigate]);


    const fetchData = async () => {
        return await axios.get(`${API_BASE_URL}user/${id}`, {
            headers: {
                Authorization: `Bearer ${cookie.jwt}`,
            }
        });
    };

    const followSubmit = async (e) => {
        e.preventDefault();
        let mode;
        if (alreadyFollowed) {
            mode = "unfollow/";
        } else {
            mode = "follow/";
        }

        try {
            await axios.post(
                `${API_BASE_URL}${mode}${id}`,
                {},
                {
                    headers: {
                        Authorization: `Bearer ${cookie.jwt}`,
                    }
                }
            );
            setAlreadyFollowed(!alreadyFollowed);
        } catch (error) {
            console.log(error);
        }
    };


    useEffect(() => {
        fetchData().then(r => {
            if (r.status === 200) return r.data;
            throw r;
        })
            .then((data) => {
                setEmail(data.email);
                setNames(data.firstName + " " + data.lastName);
                setAlreadyFollowed(data.follows);
            })
            .catch(() => {
                navigate('/');
            });
        if (email === jwt_decode(cookie.jwt)['username']) setEnableFollow(false);
        if (!alreadyFollowed) {
            setHeartIcon(follow);
        } else {
            setHeartIcon(followFilled);
        }
    });


    return (
        <Page subpage="profile">
            <div className={style.profileDiv}>
                <div className={style.user}>
                    <img className={style.bigAvatar} src={avatar} alt="avatar"/>
                    <p className={style.bigUsername}>{names}</p>
                    <p className={style.email}>{email}</p>
                    {enableFollow && <img onClick={followSubmit} className={style.follow} src={heartIcon} alt="heart"/>}
                </div>
                <div className={style.rankingType}>
                    <p>Movies</p>
                    <p>Series</p>
                </div>
                <hr className={style.line}/>
                <div className={style.counted}>
                    Rates: <div className={style.number}>21</div>
                    Want to See: <div className={style.number}>21</div>
                    Favourite: <div className={style.number}>21</div>
                </div>
                <MovieList title="Your Best Rated Movies"/>
                <MovieList title="Movies You Want to See"/>
                <MovieList title="Your Favourite Movies"/>
            </div>
        </Page>
    );
}

export default Profile;