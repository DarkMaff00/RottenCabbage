import React from 'react';
import style from './Ranking.module.css';
import Page from '../../components/Page/Page';
import Input from "../../components/Input/Input";
import MovieTab from "../../components/MovieTab/MovieTab";


function Ranking() {

    return (
        <Page subpage="ranking">
            <div className={style.rankingDiv}>
                <div className={style.headerMain}>
                    <Input type="text" placeholder="Search..." required="False" />
                    <div className={style.rankingType}>
                        <p>Movies</p>
                        <p>Series</p>
                    </div>
                    <hr className={style.line}></hr>
                </div>
                <div className={style.movies}>
                    <MovieTab number="1" title="Iron Man" genre="Sci-Fi, Action" grade="94%" mark="7,6"/>
                    <MovieTab number="1" title="Iron Man" genre="Sci-Fi, Action" grade="94%" mark="7,6"/>
                    <MovieTab number="1" title="Iron Man" genre="Sci-Fi, Action" grade="94%" mark="7,6"/>
                    <MovieTab number="1" title="Iron Man" genre="Sci-Fi, Action" grade="94%" mark="7,6"/>
                    <MovieTab number="1" title="Iron Man" genre="Sci-Fi, Action" grade="94%" mark="7,6"/>
                    <MovieTab number="1" title="Iron Man" genre="Sci-Fi, Action" grade="94%" mark="7,6"/>
                    <MovieTab number="1" title="Iron Man" genre="Sci-Fi, Action" grade="94%" mark="7,6"/>
                </div>
            </div>
        </Page>
    );
}

export default Ranking;