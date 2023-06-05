import style from './Poster.module.css';
import React from "react";
import {useNavigate} from "react-router-dom";


function Poster(props) {

    const navigate = useNavigate();
    const click = () => {
        navigate(`/movieInfo/${props.id}`)
    };

    return (
        <div className={style.posterBlock}>
            <img className={style.poster} src={props.poster} alt="poster" onClick={click}/>
            <p className={style.title}>{props.title}</p>
            {props.rate && <p className={style.grade}>{props.rate}</p>}
        </div>
    );
}

export default Poster;