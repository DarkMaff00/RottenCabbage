import React, {useEffect, useRef, useState} from 'react';
import style from './addMovie.module.css';
import Page from '../../components/Page/Page';
import FormBox from "../../components/FormBox/FormBox";
import Button from "../../components/Button/Button";
import Input from "../../components/Input/Input";
import {useCookies} from "react-cookie";
import {useNavigate} from "react-router-dom";
import axios from "axios";
import {API_BASE_URL} from "../../index";


function AddMovie() {

    const navigate = useNavigate();
    const [cookie] = useCookies(['jwt']);
    const titleRef = useRef(null);
    const [error, setError] = useState('');

    const checkAccess = async () => {
        return await axios.get(
            `${API_BASE_URL}access`,
            {
                headers: {
                    Authorization: `Bearer ${cookie.jwt}`,
                }
            }
        );
    };

    useEffect(() => {
        if (!cookie.jwt) {
            navigate('/');
        }
        checkAccess().then(r => {
            if (r.status !== 200)
                throw r;
        }).catch(() => {
            navigate('/');
        });
    }, [cookie.jwt, navigate]);

    const handleSubmit = async (e) => {
        e.preventDefault();


        const formData = {
            title: titleRef.current.value,
        };

        try {
            const response = await axios.post(`${API_BASE_URL}addMovie`, formData);
            setError(response.data);
            setTimeout(() => {
                titleRef.current.value = "";
                setError("");
            }, 2000);
        } catch (error) {
            setError(error);
        }
    };

    return (
        <Page subpage="addMovie">
            <FormBox onSubmit={handleSubmit}>
                <h1 className={style.title}>Add Movie</h1>
                <Input
                    title="Title"
                    type="text"
                    required={true}
                    ref={titleRef}
                />
                <div className={style.errorText}>{error.message}</div>
                <Button title="Add" type="submit"/>
            </FormBox>
        </Page>
    );
}

export default AddMovie;