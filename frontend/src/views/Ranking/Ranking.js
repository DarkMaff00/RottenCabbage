import React, {useEffect, useState} from 'react';
import style from './Ranking.module.css';
import Page from '../../components/Page/Page';
import Input from "../../components/Input/Input";
import MovieTab from "../../components/MovieTab/MovieTab";
import axios from "axios";
import {API_BASE_URL} from "../../index";

function Ranking() {
    const [movies, setMovies] = useState([]);
    const [searchTerm, setSearchTerm] = useState("");

    const fetchData = async () => {
        return await axios.get(`${API_BASE_URL}ranking`);
    };

    useEffect(() => {
        fetchData()
            .then((r) => {
                if (r.status === 200) return r.data;
            })
            .then((data) => {
                setMovies(data);
            });
    }, []);

    const searchMovies = (value) => {
        setSearchTerm(value);
    };

    const filteredMovies = movies.filter((movie) =>
        movie.title.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <Page subpage="ranking">
            <div className={style.rankingDiv}>
                <div className={style.headerMain}>
                    <Input
                        type="text"
                        placeholder="Search..."
                        onChange={searchMovies}
                    />
                    <hr className={style.line}></hr>
                </div>
                <div className={style.movies}>
                    {filteredMovies.map((movie, index) => (
                        <MovieTab
                            key={movie.id}
                            movieId={movie.id}
                            number={index + 1}
                            title={movie.title}
                            genre={movie.genre}
                            grade={movie.critic.toFixed(2)}
                            mark={movie.rate.toFixed(2)}
                            poster={movie.poster}
                        />
                    ))}
                </div>
            </div>
        </Page>
    );
}

export default Ranking;
