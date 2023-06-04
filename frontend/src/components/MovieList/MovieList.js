import style from './MovieList.module.css';
import React from "react";
import Poster from "../Poster/Poster";


function MovieList(props) {
    return (
        <div className={style.movieBlock}>
            <div className={style.title}>
                {props.title}
                <hr className={style.titleLine}/>
            </div>
            <div className={style.movies}>
                {props.data.map((movie) => (
                    <Poster
                        key={movie.data.id}
                        id={movie.data.id}
                        title={movie.data.title}
                        poster={movie.data.poster}
                        rate={movie.rate}
                    />
                ))}
            </div>
        </div>
    );
}

export default MovieList;