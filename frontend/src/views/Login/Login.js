import React, {useEffect, useRef, useState} from 'react';
import style from './Login.module.css';
import Page from '../../components/Page/Page';
import FormBox from '../../components/FormBox/FormBox';
import Input from '../../components/Input/Input';
import Button from '../../components/Button/Button';
import {useNavigate} from "react-router-dom";
import axios from "axios";
import {useCookies} from 'react-cookie';
import {API_BASE_URL} from "../../index";


function Login() {

    const navigate = useNavigate();
    const emailRef = useRef(null);
    const passwordRef = useRef(null);

    const [cookie, setCookie] = useCookies(['jwt']);
    const [error, setError] = useState('');

    useEffect(() => {
        if (cookie.jwt) {
            navigate('/');
        }
    }, [cookie.jwt, navigate]);

    const handleSubmit = async (e) => {
        e.preventDefault();


        const formData = {
            email: emailRef.current.value,
            password: passwordRef.current.value
        };

        try {
            const response = await axios.post(`${API_BASE_URL}api/login_check`, formData);
            const token = response.data.token;

            setCookie('jwt', token, {
                path: '/',
                expires: new Date(new Date().getTime() + 3600 * 1000),
                secure: true,
                sameSite: 'strict'
            });
            navigate('/');
        } catch (error) {
            setError(error.response.data.message);
        }
    };

    return (
        <Page subpage="login">
            <FormBox onSubmit={handleSubmit}>
                <h1 className={style.titleBox}>Login here</h1>
                <Input
                    title="Email "
                    type="text"
                    required={true}
                    ref={emailRef}
                />
                <Input
                    title="Password "
                    type="password"
                    required={true}
                    ref={passwordRef}
                />
                <div className={style.errorText}>{error}</div>
                <Button title="LOGIN" width="100%" type="submit"/>
            </FormBox>
        </Page>
    );
}

export default Login;