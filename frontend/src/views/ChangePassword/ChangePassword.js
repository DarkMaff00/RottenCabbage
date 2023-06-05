import React, {useEffect, useRef, useState} from 'react';
import style from './ChangePassword.module.css';
import Page from '../../components/Page/Page';
import FormBox from "../../components/FormBox/FormBox";
import Button from "../../components/Button/Button";
import Input from "../../components/Input/Input";
import {useNavigate} from "react-router-dom";
import {useCookies} from "react-cookie";
import axios from "axios";
import {API_BASE_URL} from "../../index";


function ChangePassword() {

    const navigate = useNavigate();
    const passwordRef = useRef(null);
    const newPasswordRef = useRef(null);
    const repeatPasswordRef = useRef(null);

    const [passwordValid, setPasswordValid] = useState(false);
    const [passwordConfirmationValid, setPasswordConfirmationValid] = useState(false);

    const [showPassword, setShowPassword] = useState(false);
    const [isFormValid, setIsFormValid] = useState(false);
    const [error, setError] = useState('');
    const [cookie] = useCookies(['jwt']);

    useEffect(() => {
        if (!cookie.jwt) {
            navigate('/');
        }
    }, [cookie.jwt, navigate]);


    const handleShowPassword = () => {
        setShowPassword(!showPassword);
    };

    const updatePasswordValue = (value) => {
        newPasswordRef.current.value = value;
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            await axios.put(
                `${API_BASE_URL}changePassword`,
                {
                    password: passwordRef.current.value,
                    new_password: newPasswordRef.current.value
                },
                {
                    headers: {
                        Authorization: `Bearer ${cookie.jwt}`,
                    }
                }
            );

            setError("Password changed successfully");
            passwordRef.current.value = "";
            newPasswordRef.current.value = "";
            repeatPasswordRef.current.value = "";
        } catch (error) {
            setError(error.response.data.message);
        }
    };

    useEffect(() => {
        setIsFormValid(passwordValid && passwordConfirmationValid);
    }, [
        passwordValid,
        passwordConfirmationValid,
    ]);

    return (
        <Page subpage="changePassword">
            <FormBox onSubmit={handleSubmit}>
                <h1 className={style.title}>Change Password</h1>
                <Input
                    title="Current Password"
                    type={showPassword ? 'text' : 'password'}
                    required={true}
                    ref={passwordRef}
                />
                <Input
                    title="Password"
                    type={showPassword ? 'text' : 'password'}
                    required={true}
                    ref={newPasswordRef}
                    correctValue={setPasswordValid}
                    onChange={updatePasswordValue}
                />
                <Input
                    title="Repeat Password"
                    type={showPassword ? 'text' : 'password'}
                    required={true}
                    ref={repeatPasswordRef}
                    passwordValue={newPasswordRef}
                    correctValue={setPasswordConfirmationValid}
                />
                <div className={style.showPassword}>
                    <input type="checkbox" id="show" name="showPassword" onChange={handleShowPassword}/>
                    <label htmlFor="show">Show Password</label>
                </div>
                <div className={style.errorText}>{error}</div>
                <Button title="CHANGE" type="submit" disabled={!isFormValid}/>
            </FormBox>
        </Page>
    );
}

export default ChangePassword;