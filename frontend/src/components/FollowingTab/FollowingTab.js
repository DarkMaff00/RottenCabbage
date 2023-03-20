import style from './FollowingTab.module.css';
import React from "react";
import MovieTab from "../MovieTab/MovieTab";
import star from  '../../images/star.png';
import avatar from '../../images/avatar.png';


const FollowingTab = props => {
    return (
        <div className={style.followingTab}>
            <hr/>
            <div className={style.userGrade}>
                <div className={style.user}>
                    <img src={avatar} alt="user-avatar"/>
                    <p>Name Username</p>
                </div>
                <div className={style.grade}>
                    gives <p className={style.number}>10</p>
                    <div>
                        <img className={style.star}  src={star} alt="star"/>
                        <img className={style.star}  src={star} alt="star"/>
                        <img className={style.star}  src={star} alt="star"/>
                        <img className={style.star}  src={star} alt="star"/>
                        <img className={style.star}  src={star} alt="star"/>
                        <img className={style.star}  src={star} alt="star"/>
                        <img className={style.star}  src={star} alt="star"/>
                        <img className={style.star}  src={star} alt="star"/>
                        <img className={style.star}  src={star} alt="star"/>
                        <img className={style.star}  src={star} alt="star"/>
                    </div>
                </div>
            </div>
            <MovieTab number="1" title="Iron Man" genre="Sci-Fi, Action" grade="94%" mark="7,6"/>
        </div>
    );
}

export default FollowingTab;