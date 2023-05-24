import style from './Button.module.css';
import React from "react";


const Button = props => {
    return (
        <button className={style.button} style={{width: props.width}} disabled={props.disabled}>
            {props.title}
        </button>
    );
}

export default Button;