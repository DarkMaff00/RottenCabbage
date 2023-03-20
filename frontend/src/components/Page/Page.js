import React from 'react';
import style from './Page.module.css';
import arrow from "../../images/arrow.svg";


const Page = props => {

    let exitButton;
    if (props.subpage === "login") {
        exitButton = <div className={style.changeText}>Signup</div>
    }
    return (
        <div className={style.app}>
            <div className={style.changeInput}>
                {exitButton}
                <img className={style.arrow} src={arrow} alt="arrow"/>
            </div>
            {props.children}
        </div>
    );
}

export default Page;