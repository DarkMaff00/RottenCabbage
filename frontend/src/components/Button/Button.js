import style from './Button.module.css';
import React from "react";


function Button(props) {
    return (
        <button className={style.button} style={{width: props.width}} disabled={props.disabled} onClick={props.onClick}>
            {props.title}
        </button>
    );
}

export default Button;