import style from './MovieTab.module.css';
import React, {useEffect, useState} from "react";
import cabbage from '../../images/logo.svg';
import star from '../../images/star.svg';
import {useNavigate} from "react-router-dom";

function MovieTab(props) {
    const [isPremier, setIsPremier] = useState(false);
    const navigate = useNavigate();

    const viewMovie = () => {
        if (!isPremier) {
            navigate('/movieInfo/' + props.movieId);
        } else {
            window.open(props.trailer, "_blank");
        }
    };

    useEffect(() => {
        const handleTypeChange = () => {
            if (props.type === "premier") {
                setIsPremier(true);
            } else {
                setIsPremier(false);
            }
        };
        handleTypeChange();
    }, [props.type]);

    return (
        <div className={`${style.movieTab} ${style.mobile}`} onClick={viewMovie}>
            <p className={style.number}>{props.number}</p>
            <img className={style.poster} src={props.poster} alt="poster"/>
            <div className={style.movieInfo}>
                <p className={style.movieTitle}>{props.title}</p>
                {
                    !isPremier ? (
                        <div className={style.movieGenre}>
                            Genre:
                            <p className={style.genreInfo}>{props.genre}</p>
                        </div>
                    ) : (
                        <p className={style.genreInfo}>{props.date}</p>
                    )
                }
            </div>
            {!isPremier ? (
                <div className={style.grade}>
                    <img src={cabbage} alt="cabbage"/>
                    <p className={style.mark}>{props.grade}</p>
                </div>
            ) : (
                <p className={style.desc}>{props.desc}</p>
            )}
            {!isPremier && (
                <div className={style.grade}>
                    <img src={star} alt="star"/>
                    <p className={style.mark}>{props.mark}</p>
                </div>
            )}
        </div>
    );
}

export default MovieTab;
