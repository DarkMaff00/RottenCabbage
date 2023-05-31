import React, {useEffect, useRef, useState} from 'react';
import {useNavigate} from 'react-router-dom';
import axios from "axios";
import style from './Signup.module.css';
import Page from '../../components/Page/Page';
import FormBox from '../../components/FormBox/FormBox';
import Input from '../../components/Input/Input';
import Button from '../../components/Button/Button';
import {API_BASE_URL} from "../../index";
import {useCookies} from "react-cookie";

function Signup() {

    const navigate = useNavigate();
    const emailRef = useRef(null);
    const firstNameRef = useRef(null);
    const lastNameRef = useRef(null);
    const passwordRef = useRef(null);
    const repeatPasswordRef = useRef(null);

    const [nameValid, setNameValid] = useState(false);
    const [emailValid, setEmailValid] = useState(false);
    const [passwordValid, setPasswordValid] = useState(false);
    const [passwordConfirmationValid, setPasswordConfirmationValid] = useState(false);
    const [lastNameValid, setLastNameValid] = useState(false);

    const [isFormValid, setIsFormValid] = useState(false);
    const [error, setError] = useState('');
    const [cookie] = useCookies(['jwt']);

    useEffect(() => {
        if (cookie.jwt) {
            navigate('/');
        }
    }, [cookie.jwt, navigate]);


    const updatePasswordValue = (value) => {
        passwordRef.current.value = value;
    };
    const handleSubmit = async (e) => {
        e.preventDefault();


        const formData = {
            email: emailRef.current.value,
            first_name: firstNameRef.current.value,
            last_name: lastNameRef.current.value,
            password: passwordRef.current.value
        };

        try {
            await axios.post(`${API_BASE_URL}signup`, formData);
            navigate('/login');
        } catch (error) {
            setError(error.response.data.message);
        }
    };

    useEffect(() => {
        setIsFormValid(nameValid && emailValid && passwordValid && passwordConfirmationValid && lastNameValid);
    }, [
        nameValid,
        emailValid,
        passwordValid,
        passwordConfirmationValid,
        lastNameValid,
    ]);

    return (
        <Page subpage="signup">
            <FormBox onSubmit={handleSubmit}>
                <h1 className={style.titleBox}>Create Account</h1>
                <Input
                    title="Email"
                    type="text"
                    required={true}
                    ref={emailRef}
                    correctValue={setEmailValid}
                />
                <Input
                    title="Name"
                    type="text"
                    required={true}
                    ref={firstNameRef}
                    correctValue={setNameValid}
                />
                <Input
                    title="Last Name"
                    type="text"
                    required={true}
                    ref={lastNameRef}
                    correctValue={setLastNameValid}
                />
                <Input
                    title="Password"
                    type="password"
                    required={true}
                    ref={passwordRef}
                    correctValue={setPasswordValid}
                    onChange={updatePasswordValue}
                />
                <Input
                    title="Repeat Password"
                    type="password"
                    required={true}
                    ref={repeatPasswordRef}
                    passwordValue={passwordRef}
                    correctValue={setPasswordConfirmationValid}
                />
                <div className={style.errorText}>{error}</div>
                <Button title="SIGN UP" width="100%" type="submit" disabled={!isFormValid}/>
            </FormBox>
        </Page>
    );
}

export default Signup;