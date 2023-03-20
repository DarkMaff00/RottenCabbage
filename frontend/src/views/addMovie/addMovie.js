import React from 'react';
import style from './addMovie.module.css';
import Page from '../../components/Page/Page';
import FormBox from "../../components/FormBox/FormBox";
import Button from "../../components/Button/Button";
import Input from "../../components/Input/Input";


function AddMovie() {

    return (
        <Page subpage="addMovie">
            <FormBox>
                <h1 className={style.title}>Add Movie</h1>
                <Input title="Title" type="text" required="True"/>
                <Input title="Direction" type="text" required="True"/>
                <Input title="Script" type="text" required="True"/>
                <Input title="Genre" type="text" required="True"/>
                <Input title="Production" type="text" required="True"/>
                <Input title="Premier" type="date" required="True"/>
                <Input title="Trailer" type="url" required="True"/>
                <Input title="Rotten Tomatoes" type="url" required="True"/>
                <p>Description</p>
                <textarea/>
                <Button title="Add"/>
            </FormBox>
        </Page>
    );
}

export default AddMovie;