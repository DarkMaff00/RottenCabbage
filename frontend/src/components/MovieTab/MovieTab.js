import style from './MovieTab.module.css';
import React from "react";
import cabbage from '../../images/logo.svg';
import star from '../../images/star.png';
import {useNavigate} from "react-router-dom";


function MovieTab(props) {

    const navigate = useNavigate();

    const viewMovie = () => {
        navigate('/movieInfo/' + props.movieId);
    };


    return (
        <div className={style.movieTab} onClick={viewMovie}>
            <p className={style.number}>{props.number}</p>
            <img className={style.poster} src={props.poster} alt="poster"/>
            <div className={style.movieInfo}>
                <p className={style.movieTitle}>{props.title}</p>
                <div className={style.movieGenre}>Genre:<p className={style.genreInfo}>{props.genre}</p></div>
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