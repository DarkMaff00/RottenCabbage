import style from './MovieList.module.css';
import React from "react";
import Poster from "../Poster/Poster";
import Button from "../Button/Button";


const MovieList = props => {
    return (
        <div className={style.movieBlock}>
            <div className={style.title}>
                {props.title}
                <hr className={style.titleLine}/>
            </div>
            <div className={style.movies}>
                <Poster/>
                <Poster/>
                <Poster/>
                <Poster/>
            </div>
            <Button title="SEE ALL" width="30%"/>
        </div>
    );
}

export default MovieList;