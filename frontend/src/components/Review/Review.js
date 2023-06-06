import style from './Review.module.css';
import React, {useEffect, useState} from "react";
import avatar from "../../images/avatar.png";
import follow from "../../images/follow.svg";
import bin from "../../images/bin.svg";
import followFilled from "../../images/followFilled.svg";
import {useCookies} from "react-cookie";
import {useNavigate} from "react-router-dom";
import axios from "axios";
import {API_BASE_URL} from "../../index";


function Review(props) {

    const navigate = useNavigate();
    const [cookie] = useCookies(['jwt']);
    const [liked, setLiked] = useState(false);
    const [deleteReview, setDeleteReview] = useState(false);
    const [likes, setLikes] = useState(props.likes);

    const deleteRev = async () => {
        await axios.delete(`${API_BASE_URL}deleteReview/${props.id}`, {
            headers: {
                Authorization: `Bearer ${cookie.jwt}`,
            }
        });
        props.refresh();
    };

    const move = () => {
      navigate(`/profile/${props.user}`);
    };


    const addLike = async () => {

        await axios.post(
            `${API_BASE_URL}likeReview/${props.id}`,
            {},
            {
                headers: {
                    Authorization: `Bearer ${cookie.jwt}`,
                },
            }
        );
    };

    useEffect(() => {

        const checkLike = async () => {
            const response = await axios.get(`${API_BASE_URL}checkLike/${props.id}`, {
                headers: {
                    Authorization: `Bearer ${cookie.jwt}`,
                }
            });
            if (response.data.liked) {
                setLiked(true);
            }
            if (response.data.owner) {
                setDeleteReview(true);
            }
        };
        const checkAccess = async () => {
            try {
                await axios.get(
                    `${API_BASE_URL}access`,
                    {
                        headers: {
                            Authorization: `Bearer ${cookie.jwt}`,
                        }
                    }
                );
                setDeleteReview(true);
            } catch (error) {
                console.log(error);
            }

        };
        if (cookie.jwt) {
            checkAccess();
            checkLike();
        }
    }, [cookie.jwt, props.id]);

    const like = () => {
        if (!cookie.jwt) {
            navigate('/login');
        } else {
            liked ? setLikes(likes - 1) : setLikes(likes + 1);
            setLiked(!liked);
            addLike();
        }
    }

    return (
        <div className={style.reviewDiv}>
            <div className={style.user}>
                <img className={style.icon} src={avatar} alt="avatar" onClick={move}/>
            </div>
            <div className={style.context}>
                <div className={style.name}>{props.email}<p className={style.date}>{props.date}</p></div>
                <div className={style.text}>{props.context}</div>
            </div>
            <div className={style.like}>
                <img onClick={like} src={liked ? followFilled : follow} alt="heart"/>
                <p>{likes > 999 ? Math.floor(likes/1000) + "k" : likes}</p>
            </div>
            {deleteReview && <img className={style.bin} onClick={deleteRev} src={bin} alt="heart"/>}
        </div>
    );
}

export default Review;