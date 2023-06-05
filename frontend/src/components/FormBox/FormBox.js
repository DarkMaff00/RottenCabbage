import React from 'react';
import styles from './FormBox.module.css';

function FormBox(props) {
    const handleSubmit = (e) => {
        e.preventDefault();
        if (typeof props.onSubmit === 'function') {
            props.onSubmit(e);
        }
    };

    return (
        <form className={styles.formBox} style={{ width: props.width }} onSubmit={handleSubmit}>
            {props.children}
        </form>
    );
}

export default FormBox;
