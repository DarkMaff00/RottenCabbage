import React, {useEffect} from 'react';
import style from './Settings.module.css';
import Page from '../../components/Page/Page';
import FormBox from "../../components/FormBox/FormBox";
import Button from "../../components/Button/Button";
import {useCookies} from "react-cookie";
import {Link, useNavigate} from "react-router-dom";
import jwt_decode from 'jwt-decode';


function Settings() {

    const navigate = useNavigate();
    const [cookie, setCookie, removeCookie] = useCookies(['jwt']);

    useEffect(() => {
        if (!cookie.jwt) {
            navigate('/');
        }
    }, [cookie.jwt, navigate]);

    const email = jwt_decode(cookie.jwt)['username'];

    const handleLogout = () => {
        removeCookie('jwt');
        navigate('/');
    };

    return (
        <Page subpage="settings">
            <FormBox>
                <div className={style.buttons}>
                    <p className={style.username}>{email}</p>
                    <Link to='/changePassword' className={style.linkStyle}><Button title="CHANGE PASSWORD" width="70%"/></Link>
                    <Link to='/deleteAccount' className={style.linkStyle}><Button title="DELETE ACCOUNT" width="70%"/></Link>
                    <Button onClick={handleLogout} title="LOG OUT" width="70%"/>
                </div>
            </FormBox>
        </Page>
    );
}

export default Settings;