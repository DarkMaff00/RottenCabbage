import style from './Poster.module.css';
import React from "react";


function Poster(props) {
    return (
        <div className={style.posterBlock}>
            <img className={style.poster} src={props.poster} alt="poster"/>
            <p className={style.title}>{props.title}</p>
            {props.rate && <p className={style.grade}>{props.rate}</p>}
        </div>
    );
}

export default Poster;