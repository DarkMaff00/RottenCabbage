import React, {useEffect, useRef, useState} from 'react';
import style from './DeleteAccount.module.css';
import Page from '../../components/Page/Page';
import FormBox from "../../components/FormBox/FormBox";
import Button from "../../components/Button/Button";
import Input from "../../components/Input/Input";
import axios from "axios";
import {useNavigate} from "react-router-dom";
import {useCookies} from 'react-cookie';
import {API_BASE_URL} from "../../index";


function DeleteAccount() {

    const navigate = useNavigate();
    const passwordRef = useRef(null);
    const [error, setError] = useState('');
    const [cookies, setCookie, removeCookie] = useCookies(['jwt']);
    const [showPassword, setShowPassword] = useState(false);


    useEffect(() => {
        if (!cookies.jwt) {
            navigate('/');
        }
    }, [cookies.jwt, navigate]);

    const handleShowPassword = () => {
        setShowPassword(!showPassword);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            await axios.delete(
                `${API_BASE_URL}deleteAccount`,
                {
                    headers: {
                        Authorization: `Bearer ${cookies.jwt}`,
                    },
                    data: {
                        password: passwordRef.current.value,
                    },
                }
            );
            removeCookie('jwt', {path: '/'});
            navigate('/');
        } catch (error) {
            setError(error.response.data.message);
        }
    };

    return (
        <Page subpage="deleteAccount">
            <FormBox onSubmit={handleSubmit}>
                <h1 className={style.title}>Delete Account</h1>
                <Input
                    title="Password "
                    type={showPassword ? 'text' : 'password'}
                    required={true}
                    ref={passwordRef}
                />
                <div className={style.showPassword}>
                    <input type="checkbox" id="show" name="showPassword" onChange={handleShowPassword}/>
                    <label htmlFor="show">Show Password</label>
                </div>
                <div className={style.errorText}>{error}</div>
                <Button title="Delete" type="submit"/>
            </FormBox>
        </Page>
    );
}

export default DeleteAccount;