import React from 'react';
import style from './Login.module.css';
import Page from '../../components/Page/Page';
import FormBox from '../../components/FormBox/FormBox';
import Input from '../../components/Input/Input';
import Button from '../../components/Button/Button';


function Login() {

    return (
        <Page subpage= "login">
            <FormBox>
                <h1 className={style.titleBox}>You can Login here</h1>
                <Input title="Email" type="text" required="True"/>
                <Input title="Password" type="password" required="True"/>
                <Button title="LOGIN" width="100%"/>
            </FormBox>
        </Page>
    );
}

export default Login;