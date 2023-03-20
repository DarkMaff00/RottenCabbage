import React from 'react';
import style from './Settings.module.css';
import Page from '../../components/Page/Page';
import FormBox from "../../components/FormBox/FormBox";
import Button from "../../components/Button/Button";


function Settings() {

    return (
        <Page subpage="settings">
            <FormBox>
                <div className={style.buttons}>
                    <p className={style.username}>Name Surname</p>
                    <p>konrad.woj77@gmail.com</p>
                    <Button title="CHANGE PASSWORD" width="70%"/>
                    <Button title="DELETE ACCOUNT" width="70%"/>
                    <Button title="LOG OUT" width="70%"/>
                </div>
            </FormBox>
        </Page>
    );
}

export default Settings;