import React from 'react';
import {Link} from "react-router-dom";
import logo from '../../images/logo.svg';
import style from './Page.module.css';
import arrow from "../../images/arrow.svg";


const Page = props => {

    let exitButton;
    if (props.subpage === "login") {
        exitButton = [
            <Link to='/signup'>
                <div className={style.changeText}>Signup</div>
            </Link>
        ]
    } else if (props.subpage === "signup") {
        exitButton = [
            <Link to='/login'>
                <div className={style.changeText}>Login</div>
            </Link>
        ]
    }
    return (
        <div className={style.app}>
            <div className={style.changeInput}>
                <Link to='/login'>
                    <div className={style.logoDiv}>
                        <img className={style.logoImage} src={logo} alt="log"/>
                        <div className={style.logoText}>
                            <p>Rotten</p>
                            <p>Cabbage</p>
                        </div>
                    </div>
                </Link>
                <div className={style.exitButton}>
                    {exitButton}
                    <img className={style.arrow} src={arrow} alt="arrow"/>
                </div>
            </div>
            {props.children}
        </div>
    );
}

export default Page;