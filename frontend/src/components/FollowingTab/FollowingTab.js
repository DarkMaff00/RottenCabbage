import style from './FollowingTab.module.css';
import React from "react";
import avatar from '../../images/avatar.png';
import {useNavigate} from "react-router-dom";


function FollowingTab(props) {

    const navigate = useNavigate();

    const handleClick = () => {
        navigate(`/profile/${props.id}`)
    };

    return (
        <div onClick={handleClick} className={style.followingTab}>
            <div className={style.userGrade}>
                <img src={avatar} alt="user-avatar"/>
                <p className={style.name}>{props.name} {props.lastName}</p>
                <p>{props.email}</p>
            </div>
        </div>
    );
}

export default FollowingTab;