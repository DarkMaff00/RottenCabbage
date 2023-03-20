import React from 'react';
import style from './MovieInfo.module.css';
import Page from '../../components/Page/Page';
import poster from '../../images/bigPoster.png';
import Button from "../../components/Button/Button";
import cabbage from "../../images/logo.svg";
import star from "../../images/star.png";
import avatar from "../../images/avatar.png";
import follow from "../../images/follow.svg";
import wantSee from "../../images/eye.svg";
import FormBox from "../../components/FormBox/FormBox";
import Input from "../../components/Input/Input";
import Review from "../../components/Review/Review";



function MovieInfo() {

    return (
        <Page subpage="movieInfo">
            <div className={style.section}>
                <div className={style.movie}>
                    <img className={style.poster} src={poster} alt="iron-man"/>
                    <p>Iron Man</p>
                    <Button title="TRAILER" width="80%"/>
                </div>
                <div className={style.info}>
                    <div className={style.description}>
                        Powered by Robert Downey Jr.'s vibrant charm, Iron Man turbo-charges the superhero genre with a
                        deft intelligence and infectious sense of fun.
                    </div>
                    <div className={style.data}>
                        <p>Direction:<p className={style.text}>Jon Favreau</p></p>
                        <p>Script:<p className={style.text}>Hawk Ostby, Mark Fergus</p></p>
                        <p>Genre:<p className={style.text}>Sci-Fi, Action</p></p>
                        <p>Production:<p className={style.text}>USA</p></p>
                        <p>Premier:<p className={style.text}>14th April 2008</p></p>
                    </div>
                    <div className={style.grades}>
                        <div className={style.pair}>
                            <img className={style.icon} src={cabbage} alt="cabbage"/>
                            <p className={style.number}>94%</p>
                        </div>
                        <div className={style.pair}>
                            <img className={style.icon} src={star} alt="star"/>
                            <p className={style.number}>7,6</p>
                        </div>
                        <div className={style.pair}>
                            <img className={style.icon} src={avatar} alt="avatar"/>
                            <p className={style.number}>10</p>
                        </div>
                    </div>
                    <div className={style.extras}>
                        <div>
                            <img className={style.smallIcon} src={star} alt="star"/>
                            <img className={style.smallIcon} src={star} alt="star"/>
                            <img className={style.smallIcon} src={star} alt="star"/>
                            <img className={style.smallIcon} src={star} alt="star"/>
                            <img className={style.smallIcon} src={star} alt="star"/>
                            <img className={style.smallIcon} src={star} alt="star"/>
                            <img className={style.smallIcon} src={star} alt="star"/>
                            <img className={style.smallIcon} src={star} alt="star"/>
                            <img className={style.smallIcon} src={star} alt="star"/>
                            <img className={style.smallIcon} src={star} alt="star"/>
                        </div>
                        <img className={style.smallIcon} src={follow} alt="heart"/>
                        <img className={style.smallIcon} src={wantSee} alt="eye"/>
                    </div>
                </div>
            </div>
            <FormBox>
                <Input title="Add Review" />
            </FormBox>
            <FormBox>
                <Review/>
                <Review/>
                <Review/>
                <Review/>
            </FormBox>
        </Page>
    );
}

export default MovieInfo;