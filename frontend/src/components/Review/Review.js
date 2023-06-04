import style from './Review.module.css';
import React, {useEffect, useState} from "react";
import avatar from "../../images/avatar.png";
import follow from "../../images/follow.svg";
import followFilled from "../../images/followFilled.svg";
import {useCookies} from "react-cookie";
import {useNavigate} from "react-router-dom";
import axios from "axios";
import {API_BASE_URL} from "../../index";


function Review(props) {

    const navigate = useNavigate();
    const [cookie] = useCookies(['jwt']);
    const [liked, setLiked] = useState(false);

    const checkLike = async () => {
        const response = await axios.get(`${API_BASE_URL}checkLike/${props.id}`, {
            headers: {
                Authorization: `Bearer ${cookie.jwt}`,
            }
        });
        if (response.data) {
            setLiked(true);
        }
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
        checkLike();
    }, []);

    const like = () => {
        if (!cookie.jwt) {
            navigate('/login');
        } else {
            setLiked(!liked);
            addLike();
        }
    }

    return (
        <div className={style.reviewDiv}>
            <div className={style.user}>
                <img className={style.icon} src={avatar} alt="avatar"/>
            </div>
            <div className={style.context}>
                <div className={style.name}>{props.email}<p className={style.date}>{props.date}</p></div>
                <div className={style.text}>{props.context}</div>
            </div>
            <div className={style.like}>
                <img onClick={like} src={liked ? followFilled : follow} alt="heart"/>
                <p>{props.likes}</p>
            </div>
        </div>
    );
}

export default Review;