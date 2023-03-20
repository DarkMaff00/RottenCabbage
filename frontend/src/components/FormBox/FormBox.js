import React from 'react';
import style from './FormBox.module.css';


const FormBox = props => {
    return (
        <div className={style.formBox} style={{width: props.width}} >
            {props.children}
        </div>
    );
}

export default FormBox;