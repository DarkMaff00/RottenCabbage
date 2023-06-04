import style from './Review.module.css';
import React from "react";
import avatar from "../../images/avatar.png";
import follow from "../../images/follow.svg";


function Review(props) {
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
                <img src={follow} alt="heart"/>
                <p>{props.likes}</p>
            </div>
        </div>
    );
}

export default Review;