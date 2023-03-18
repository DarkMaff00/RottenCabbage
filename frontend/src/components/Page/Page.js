import style from './Page.module.css';
import frontWave from '../../images/wave1.svg';
import backWave from '../../images/wave2.svg';
import React from "react";


function Page() {

    return (
        <div className={style.app}>
            <div className={style.content}>
            </div>
            <div className={style.waves}>
                <img className={style.backWave} src={backWave} alt="bottom-wave"/>
                <img className={style.frontWave} src={frontWave} alt="bottom-wave"/>
            </div>
        </div>
    );
}

export default Page;