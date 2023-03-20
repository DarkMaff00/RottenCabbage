import style from './Input.module.css';
import React from "react";


const Input = props => {
    return (
        <div className={style.alignInput}>
            <p className={style.titleInput}>{props.title}</p>
            <input
                className={style.styleInput}
                type={props.type}
                placeholder={props.placeholder}
                required={props.required}
            />
        </div>
    );
}

export default Input;