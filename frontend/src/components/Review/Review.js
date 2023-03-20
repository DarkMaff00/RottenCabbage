import style from './Review.module.css';
import React from "react";
import avatar from "../../images/avatar.png";
import follow from "../../images/follow.svg";


const Review = props => {
    return (
        <div className={style.reviewDiv}>
            <div className={style.user}>
                <img className={style.icon} src={avatar} alt="avatar"/>
                <p>Name</p>
            </div>
            <div className={style.context}>
                <div className={style.date}>2021-12-31 12:11</div>
                <div className={style.text}>Very good movie! Let's go Marvel !!! That movie is awesome.</div>
            </div>
            <div className={style.like}>
                <img src={follow} alt="heart"/>
                <p>15</p>
            </div>
        </div>
    );
}

export default Review;