import React from 'react';
import style from './Signup.module.css';
import Page from '../../components/Page/Page';
import FormBox from '../../components/FormBox/FormBox';
import Input from '../../components/Input/Input';
import Button from '../../components/Button/Button';


function Signup() {

    return (
        <Page subpage= "signup">
            <FormBox>
                <h1 className={style.titleBox}>You can Create Account here</h1>
                <Input title="Email" type="text" required="True"/>
                <Input title="Name" type="text" required="True"/>
                <Input title="Surname" type="text" required="True"/>
                <Input title="Password" type="password" required="True"/>
                <Input title="Repeat Password" type="password" required="True"/>
                <Button title="SIGN UP" width="100%"/>
            </FormBox>
        </Page>
    );
}

export default Signup;