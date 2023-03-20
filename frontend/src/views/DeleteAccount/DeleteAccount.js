import React from 'react';
import style from './DeleteAccount.module.css';
import Page from '../../components/Page/Page';
import FormBox from "../../components/FormBox/FormBox";
import Button from "../../components/Button/Button";
import Input from "../../components/Input/Input";


function DeleteAccount() {

    return (
        <Page subpage="deleteAccount">
            <FormBox>
                <h1 className={style.title}>Delete Account</h1>
                <Input title="Password" type="password" required="True"/>
                <Button title="Delete"/>
            </FormBox>
        </Page>
    );
}

export default DeleteAccount;