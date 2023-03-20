import style from './Poster.module.css';
import React from "react";
import poster from '../../images/poster.png';


const Poster = props => {
    return (
        <div className={style.posterBlock}>
            <img className={style.poster} src={poster} alt="poster"/>
            <p className={style.title}>Iron Man</p>
        </div>
    );
}

export default Poster;