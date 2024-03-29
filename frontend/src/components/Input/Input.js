import React, {forwardRef, useState} from 'react';
import styles from './Input.module.css';

const Input = forwardRef((props, ref) => {
    const [error, setError] = useState('');

    const validateInput = (value, rules) => {
        const {minLength, maxLength, regex, errorMessage} = rules;

        if (value.length < minLength || value.length > maxLength) {
            setError(errorMessage);
            props.correctValue?.(false);
        } else if (!regex.test(value)) {
            setError(errorMessage);
            props.correctValue?.(false);
        } else {
            setError('');
            props.correctValue?.(true);
        }
    };

    const handleChange = (e) => {
        const {value} = e.target;

        switch (props.title) {
            case 'Email':
                validateInput(value, {
                    regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                    errorMessage: 'Invalid email format',
                });
                break;
            case 'Password':
                validateInput(value, {
                    minLength: 8,
                    maxLength: Infinity,
                    regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[a-zA-Z\d!@#$%^&*(),.?":{}|<>]+$/,
                    errorMessage:
                        'Password should be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character',
                });
                props.onChange?.(value);
                break;
            case 'Repeat Password':
                const passwordValue = props.passwordValue.current.value;
                if (value !== passwordValue) {
                    setError('Passwords do not match');
                    props.correctValue?.(false);
                } else {
                    setError('');
                    props.correctValue?.(true);
                }
                break;
            default:
                props.onChange?.(value);
                break;
        }
    };

    return (
        <div className={styles.alignInput}>
            <p className={styles.titleInput}>{props.title}</p>
            <input
                maxLength={props.maxlength}
                ref={ref}
                className={`${styles.styleInput} ${error && styles.errorInput}`}
                type={props.type}
                placeholder={props.placeholder}
                required={props.required}
                onChange={handleChange}

            />
            {error && <p className={styles.errorText}>{error}</p>}
        </div>
    );
});

export default Input;
