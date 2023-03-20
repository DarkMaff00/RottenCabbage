import style from './MovieTab.module.css';
import React from "react";
import cabbage from '../../images/logo.svg';
import star from  '../../images/star.png';
import poster from '../../images/poster.png'


const MovieTab = props => {
    return (
        <div className={style.movieTab}>
            <p className={style.number}>{props.number}</p>
            <img className={style.poster} src={poster} alt="iron-man poster"/>
            <div className={style.movieInfo}>
                <p className={style.movieTitle}>{props.title}</p>
                <div className={style.movieGenre}>Genre <p className={style.genreInfo}>{props.genre}</p></div>
            </div>
            <div className={style.grade}>
                <img src={cabbage} alt="cabbage"/>
                <p className={style.mark}>{props.grade}</p>
            </div>
            <div className={style.grade}>
                <img src={star} alt="star"/>
                <p className={style.mark}>{props.mark}</p>
            </div>
        </div>
);
}

export default MovieTab;