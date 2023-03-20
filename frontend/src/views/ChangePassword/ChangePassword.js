import React from 'react';
import style from './ChangePassword.module.css';
import Page from '../../components/Page/Page';
import FormBox from "../../components/FormBox/FormBox";
import Button from "../../components/Button/Button";
import Input from "../../components/Input/Input";


function ChangePassword() {

    return (
        <Page subpage="changePassword">
            <FormBox>
                <h1 className={style.title}>Change Password</h1>
                <Input title="Current Password" type="password" required="True"/>
                <Input title="New Password" type="password" required="True"/>
                <Input title="Repeat Password" type="password" required="True"/>
                <Button title="Change"/>
            </FormBox>
        </Page>
    );
}

export default ChangePassword;