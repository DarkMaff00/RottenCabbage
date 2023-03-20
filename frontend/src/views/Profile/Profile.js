import React from 'react';
import style from './Profile.module.css';
import Page from '../../components/Page/Page';
import avatar from '../../images/bigAvatar.png';
import MovieList from "../../components/MovieList/MovieList";
import follow from "../../images/follow.svg";



function Profile() {

    return (
        <Page subpage="profile">
            <div className={style.profileDiv}>
                <div className={style.user}>
                    <img className={style.bigAvatar} src={avatar} alt="avatar"/>
                    <p className={style.bigUsername}>Name Surname </p>
                    <p className={style.email}>konrad.woj77@gmail.com</p>
                    <img className={style.follow} src={follow} alt="heart"/>
                </div>
                <div className={style.rankingType}>
                    <p>Movies</p>
                    <p>Series</p>
                </div>
                <hr/>
                <div className={style.counted}>
                    Rates: <div className={style.number}>21</div>
                    Want to See: <div className={style.number}>21</div>
                    Favourite: <div className={style.number}>21</div>
                </div>
                <MovieList title="Your Best Rated Movies"/>
                <MovieList title="Movies You Want to See"/>
                <MovieList title="Your Favourite Movies"/>
            </div>
        </Page>
    );
}

export default Profile;