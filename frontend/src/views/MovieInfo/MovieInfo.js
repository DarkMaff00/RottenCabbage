import React, {useEffect, useRef, useState} from 'react';
import style from './MovieInfo.module.css';
import Page from '../../components/Page/Page';
import Button from "../../components/Button/Button";
import cabbage from "../../images/logo.svg";
import star from "../../images/star.svg";
import greyStar from "../../images/greyStar.svg";
import avatar from "../../images/avatar.png";
import follow from "../../images/follow.svg";
import followFilled from "../../images/followFilled.svg";
import wantSee from "../../images/eye.svg";
import wantSeeFilled from "../../images/eyeFilled.svg";
import FormBox from "../../components/FormBox/FormBox";
import Input from "../../components/Input/Input";
import Review from "../../components/Review/Review";
import axios from "axios";
import {API_BASE_URL} from "../../index";
import {useNavigate, useParams} from "react-router-dom";
import {useCookies} from "react-cookie";


function MovieInfo() {

    const navigate = useNavigate();
    const descRef = useRef(null);
    const {id} = useParams();
    const [movieInfo, setMovieInfo] = useState([]);
    const [cookie] = useCookies(['jwt']);
    const [fav, setFav] = useState(false);
    const [wts, setWts] = useState(false);
    const [logged, setLogged] = useState(false);
    const [userRate, setUserRate] = useState(0);
    const [movieRate, setMovieRate] = useState(0);
    const [message, setMessage] = useState('');
    const [reviews, setReviews] = useState([]);

    const fetchData = async () => {
        return await axios.get(`${API_BASE_URL}movieInfo/${id}`);
    };


    const getReviews = async () => {
        const response = await axios.get(`${API_BASE_URL}getReviews/${id}`);
        setReviews(response.data);
    };


    const checkState = async () => {
        return await axios.get(`${API_BASE_URL}checkStates/${id}`, {
            headers: {
                Authorization: `Bearer ${cookie.jwt}`,
            }
        });
    };

    const addReview = async (e) => {
        e.preventDefault();

        const response = await axios.post(
            `${API_BASE_URL}addReview/${id}`,
            {
                desc: descRef.current.value,
            },
            {
                headers: {
                    Authorization: `Bearer ${cookie.jwt}`,
                },
            }
        );
        setMessage(response.data);
    };

    const getRating = async () => {
        const response = await axios.get(`${API_BASE_URL}getRate/${id}`);
        setMovieRate(response.data);
    };
    const handleSubmit = async (value) => {
        return await axios.post(`${API_BASE_URL}addFavourite/${id}`,
            {
                'isFavourite': value,
            },
            {
                headers: {
                    Authorization: `Bearer ${cookie.jwt}`,
                }
            });
    };

    const addRate = async (value) => {
        if (!logged) {
            navigate('/login');
            return;
        }
        setUserRate(value);
        return await axios.post(`${API_BASE_URL}rateMovie/${id}`,
            {
                'rate': value,
            },
            {
                headers: {
                    Authorization: `Bearer ${cookie.jwt}`,
                }
            });
    };


    useEffect(() => {
        if (cookie.jwt) {
            setLogged(true);
            checkState().then(r => {
                if (r.status === 200) return r.data;
            })
                .then((data) => {
                    if (data.favourite) {
                        setFav(true);
                    }
                    if (data.wantToSee) {
                        setWts(true);
                    }
                    if (data.rate !== 0) {
                        setUserRate(data.rate);
                    }
                })
        }
        getReviews();
        fetchData().then(r => {
            if (r.status === 200) return r.data;
        })
            .then((data) => {
                setMovieInfo(data);
                setMovieRate(data.rate);
            }).catch(() => {
            navigate('/');
        })
    }, []);

    const showTrailer = () => {
        window.open(movieInfo.trailerKey, "_blank");
    };

    const addFavourite = () => {
        if (!logged) {
            navigate('/login');
        } else {
            handleSubmit(true).then(r => {
                if (r.status === 200) return r.data;
            })
                .then(() => {
                    setFav(!fav);
                })
        }
    }

    const addWantToWatch = () => {
        if (!logged) {
            navigate('/login');
        } else {
            handleSubmit(false).then(r => {
                if (r.status === 200) return r.data;
            })
                .then(() => {
                    setWts(!wts);
                })
        }
    }

    const useRenderStars = () => {
        const rating = parseFloat(userRate);
        const [hoveredStar, setHoveredStar] = useState(rating - 1);

        useEffect(() => {
            setHoveredStar(rating - 1);
        }, [rating]);

        const handleStarHover = (index) => {
            setHoveredStar(index);
        };

        const handleStarLeave = () => {
            setHoveredStar(rating - 1);
        };

        const yellowStars = rating;


        if (rating !== 0) {
            getRating();
        }


        return (
            <>
                {Array.from({length: yellowStars}, (_, index) => (
                    <img
                        key={index}
                        className={style.smallIcon}
                        src={index <= hoveredStar ? star : greyStar}
                        alt="star"
                        onMouseEnter={() => handleStarHover(index)}
                        onMouseLeave={handleStarLeave}
                        onClick={() => {
                            addRate(index + 1);
                            getRating()
                        }}
                    />
                ))}
                {Array.from({length: 10 - yellowStars}, (_, index) => (
                    <img
                        key={index + yellowStars}
                        className={style.smallIcon}
                        src={index + yellowStars <= hoveredStar ? star : greyStar}
                        alt="star"
                        onMouseEnter={() => handleStarHover(index + yellowStars)}
                        onMouseLeave={handleStarLeave}
                        onClick={() => {
                            addRate(index + yellowStars + 1);
                            getRating()
                        }}
                    />
                ))}
            </>
        );
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
                            <p className={style.number}>{movieRate.toFixed(2)}</p>
                        </div>
                        {logged &&
                            <div className={style.pair}>
                                <img className={style.icon} src={avatar} alt="avatar"/>
                                <p className={style.number}>{userRate === 0 ? '-' : userRate}</p>
                            </div>
                        }
                    </div>
                    <div className={style.extras}>
                        <div>
                            <div>{useRenderStars()}</div>
                        </div>
                        <img className={style.smallIcon} src={fav ? (followFilled) : (follow)}
                             onClick={addFavourite} alt="heart"/>
                        <img className={style.smallIcon} src={wts ? (wantSeeFilled) : (wantSee)}
                             onClick={addWantToWatch} alt="eye"/>
                    </div>
                </div>
            </div>
            {logged &&
                <FormBox onSubmit={addReview}>
                    <Input
                        title="Add review"
                        type="text"
                        required={true}
                        maxlength={200}
                        ref={descRef}
                    />
                    <p>{message}</p>
                    <Button title="ADD" type="submit"></Button>
                </FormBox>
            }
            <FormBox>
                {reviews.map((review) => (
                    <Review
                        key={review.id}
                        id={review.id}
                        email={review.email}
                        context={review.context}
                        likes={review.likes}
                        date={review.date.date.split(' ')[0]}
                    />
                ))}
            </FormBox>
        </Page>
    );
}

export default MovieInfo;