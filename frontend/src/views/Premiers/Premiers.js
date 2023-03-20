import React from 'react';
import style from './Premiers.module.css';
import Page from '../../components/Page/Page';
import MovieTab from "../../components/MovieTab/MovieTab";



function Premiers() {

    return (
        <Page subpage="premiers">
            <div className={style.premiersDiv}>
                <MovieTab number="1" title="Iron Man" genre="Sci-Fi, Action" grade="94%" mark="7,6" premier="no" date="2023-04-21"/>
                <MovieTab number="1" title="Iron Man" genre="Sci-Fi, Action" grade="94%" mark="7,6" premier="no" date="2023-04-21"/>
            </div>
        </Page>
    );
}

export default Premiers;