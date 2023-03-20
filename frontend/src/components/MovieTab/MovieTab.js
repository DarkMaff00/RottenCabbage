import style from './MovieTab.module.css';
import React from "react";
import cabbage from '../../images/logo.svg';
import star from '../../images/star.png';
import poster from '../../images/poster.png'


const MovieTab = props => {
    let after_premier;
    if (props.premier === "no") {
        after_premier = [
            <div className={style.grade}>
                Premier:
                <p className={style.date}>{props.date}</p>
            </div>
        ]
    } else {
        after_premier = [
            <div className={style.grade}>
                <img src={cabbage} alt="cabbage"/>
                <p className={style.mark}>{props.grade}</p>
            </div>,
            <div className={style.grade}>
                <img src={star} alt="star"/>
                <p className={style.mark}>{props.mark}</p>
            </div>
        ]
    }
    return (
        <div className={style.movieTab}>
            <p className={style.number}>{props.number}</p>
            <img className={style.poster} src={poster} alt="iron-man poster"/>
            <div className={style.movieInfo}>
                <p className={style.movieTitle}>{props.title}</p>
                <div className={style.movieGenre}>Genre <p className={style.genreInfo}>{props.genre}</p></div>
            </div>
            {after_premier}
        </div>
    );
}

export default MovieTab;