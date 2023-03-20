import style from './NavBar.module.css';
import React from "react";
import {Link} from "react-router-dom";
import logo from "../../images/logo.svg";
import arrow from "../../images/arrow.svg";
import avatar from '../../images/avatar.png';
import userArrow from '../../images/user-arrow.svg';

const NavBar = props => {

    const NONE = {display: 'none'};
    let show;
    let rankingColor;
    let followColor;
    let premierColor;
    let endButton;
    if (props.type === "login") {
        endButton = [
            <Link to='/signup'>
                <div className={style.exitButton}>
                    <div className={style.changeText}>Signup</div>
                    <img className={style.arrow} src={arrow} alt="arrow"/>
                </div>
            </Link>
        ]
        show = NONE;
    } else if (props.type === "signup") {
        endButton = [
            <Link to='/login'>
                <div className={style.exitButton}>
                    <div className={style.changeText}>Login</div>
                    <img className={style.arrow} src={arrow} alt="arrow"/>
                </div>
            </Link>
        ]
        show = NONE;
    } else {
        endButton = [
            <div className={style.userDiv}>
                <img src={avatar} alt="sample-avatar"/>
                <p>Name Surname</p>
                <img src={userArrow} alt="userArrow"/>
            </div>
        ]
        if (props.type === "ranking") {
            rankingColor = {color: 'white'}
        }
        if (props.type === "following") {
            followColor = {color: 'white'}
        }
        if (props.type === "premiers") {
            premierColor = {color: 'white'}
        }
    }

    return (
        <div className={style.changeInput}>
            <div className={style.tabs}>
                <Link to='/'>
                    <div className={style.logoDiv}>
                        <img className={style.logoImage} src={logo} alt="log"/>
                        <div className={style.logoText}>
                            <p>Rotten</p>
                            <p>Cabbage</p>
                        </div>
                    </div>
                </Link>
                <div style={show} className={style.menuTabs}>
                    <Link to='/ranking'>
                        <p style={rankingColor}>Rankings</p>
                    </Link>
                    <Link to='/following'>
                        <p style={followColor}>Following</p>
                    </Link>
                    <Link to='/premiers'>
                        <p style={premierColor}>Premiers</p>
                    </Link>
                </div>
            </div>
            {endButton}
        </div>
    );
}

export default NavBar;