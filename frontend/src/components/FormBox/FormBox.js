import React from 'react';
import style from './FormBox.module.css';


const FormBox = (props) => {
    const handleSubmit = (e) => {
        e.preventDefault();
        if (typeof props.onSubmit === 'function') {
            props.onSubmit(e);
        }
    };

    return (
        <form className={style.formBox} style={{width: props.width}} onSubmit={handleSubmit}>
            {props.children}
        </form>
    );
}

export default FormBox;