import React, {useEffect, useState} from 'react';
import style from './MovieInfo.module.css';
import Page from '../../components/Page/Page';
import Button from "../../components/Button/Button";
import cabbage from "../../images/logo.svg";
import star from "../../images/star.png";
import avatar from "../../images/avatar.png";
import follow from "../../images/follow.svg";
import wantSee from "../../images/eye.svg";
import FormBox from "../../components/FormBox/FormBox";
import Input from "../../components/Input/Input";
import Review from "../../components/Review/Review";
import axios from "axios";
import {API_BASE_URL} from "../../index";
import {useParams} from "react-router-dom";


function MovieInfo() {

    const {id} = useParams();
    const [movieInfo, setMovieInfo] = useState([]);

    const fetchData = async () => {
        return await axios.get(`${API_BASE_URL}movieInfo/${id}`);
    };

    useEffect(() => {
        fetchData().then(r => {
            if (r.status === 200) return r.data;
        })
            .then((data) => {
                setMovieInfo(data);
            })
    }, []);

    console.log(movieInfo);

    const showTrailer = () => {
        window.open(movieInfo.trailerKey, "_blank");
    };


    return (
        <Page subpage="movieInfo">
            <div className={style.section}>
                <div className={style.movie}>
                    <img className={style.poster} src={movieInfo.poster} alt="poster"/>
                    <p>{movieInfo.title}</p>
                    <Button title="TRAILER" width="80%" onClick={showTrailer}/>
                </div>
                <div className={style.info}>
                    <div className={style.description}>{movieInfo.desc}</div>
                    <div className={style.data}>
                        <p>Direction:<p className={style.text}>{movieInfo.director}</p></p>
                        <p>Genre:<p className={style.text}>{movieInfo.genre}</p></p>
                        <p>Production:<p className={style.text}>{movieInfo.production}</p></p>
                        <p>Premier:<p className={style.text}>{movieInfo.release}</p></p>
                    </div>
                    <div className={style.grades}>
                        <div className={style.pair}>
                            <img className={style.icon} src={cabbage} alt="cabbage"/>
                            <p className={style.number}>{movieInfo?.critic?.toFixed(2)}</p>
                        </div>
                        <div className={style.pair}>
                            <img className={style.icon} src={star} alt="star"/>
                            <p className={style.number}>{movieInfo?.rate?.toFixed(2)}</p>
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
                <Input title="Add Review"/>
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