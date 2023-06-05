import React, {useEffect, useRef, useState} from 'react';
import {useParams, useNavigate} from 'react-router-dom';
import {useCookies} from 'react-cookie';
import axios from 'axios';
import style from './MovieInfo.module.css';
import Page from '../../components/Page/Page';
import Button from '../../components/Button/Button';
import cabbage from '../../images/logo.svg';
import star from '../../images/star.svg';
import greyStar from '../../images/greyStar.svg';
import avatar from '../../images/avatar.png';
import follow from '../../images/follow.svg';
import followFilled from '../../images/followFilled.svg';
import wantSee from '../../images/eye.svg';
import wantSeeFilled from '../../images/eyeFilled.svg';
import FormBox from '../../components/FormBox/FormBox';
import Input from '../../components/Input/Input';
import Review from '../../components/Review/Review';
import {API_BASE_URL} from '../../index';

function MovieInfo() {
    const navigate = useNavigate();
    const descRef = useRef(null);
    const {id} = useParams();
    const [cookie] = useCookies(['jwt']);
    const [movieInfo, setMovieInfo] = useState([]);
    const [fav, setFav] = useState(false);
    const [wts, setWts] = useState(false);
    const [logged, setLogged] = useState(false);
    const [userRate, setUserRate] = useState(0);
    const [movieRate, setMovieRate] = useState(0);
    const [message, setMessage] = useState('');
    const [reviews, setReviews] = useState([]);

    const fetchData = async () => {
        const {data} = await axios.get(`${API_BASE_URL}movieInfo/${id}`);
        return data;
    };

    const getReviews = async () => {
        const response = await axios.get(`${API_BASE_URL}getReviews/${id}`);
        setReviews(response.data);
    };

    const checkState = async () => {
        const {data} = await axios.get(`${API_BASE_URL}checkStates/${id}`, {
            headers: {
                Authorization: `Bearer ${cookie.jwt}`,
            },
        });
        return data;
    };

    const addReview = async (e) => {
        e.preventDefault();

        try {
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
            getReviews();
        } catch (error) {
            setMessage(error.response.data);
        }
    };

    const getRating = async () => {
        const response = await axios.get(`${API_BASE_URL}getRate/${id}`);
        setMovieRate(response.data);
    };

    const handleSubmit = async (value) => {
        await axios.post(
            `${API_BASE_URL}addFavourite/${id}`,
            {
                isFavourite: value,
            },
            {
                headers: {
                    Authorization: `Bearer ${cookie.jwt}`,
                },
            }
        );
    };

    const addRate = async (value) => {
        if (!logged) {
            navigate('/login');
            return;
        }
        setUserRate(value);
        await axios.post(
            `${API_BASE_URL}rateMovie/${id}`,
            {
                rate: value,
            },
            {
                headers: {
                    Authorization: `Bearer ${cookie.jwt}`,
                },
            }
        );
    };

    useEffect(() => {
        if (cookie.jwt) {
            setLogged(true);
            checkState()
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
                .catch(() => {
                    navigate('/');
                });
        }
        getReviews();
        fetchData()
            .then((data) => {
                setMovieInfo(data);
                setMovieRate(data.rate);
            })
            .catch(() => {
                navigate('/');
            });
    }, []);

    const showTrailer = () => {
        window.open(movieInfo.trailerKey, '_blank');
    };

    const addFavourite = () => {
        if (!logged) {
            navigate('/login');
        } else {
            setFav(!fav);
            handleSubmit(true);
        }
    };

    const addWantToWatch = () => {
        if (!logged) {
            navigate('/login');
        } else {
            setWts(!wts);
            handleSubmit(false);
        }
    };

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
                            getRating();
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
                            getRating();
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
                        <p>Direction:<span className={style.text}>{movieInfo.director}</span></p>
                        <p>Genre:<span className={style.text}>{movieInfo.genre}</span></p>
                        <p>Production:<span className={style.text}>{movieInfo.production}</span></p>
                        <p>Premier:<span className={style.text}>{movieInfo.release}</span></p>
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
                        refresh={getReviews}
                    />
                ))}
            </FormBox>
        </Page>
    );
}

export default MovieInfo;
