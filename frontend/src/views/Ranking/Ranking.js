import React, {useEffect, useState} from 'react';
import style from './Ranking.module.css';
import Page from '../../components/Page/Page';
import Input from '../../components/Input/Input';
import MovieTab from '../../components/MovieTab/MovieTab';
import Loading from "../../components/Loading/loading";
import axios from 'axios';
import {API_BASE_URL} from '../../index';

function Ranking() {
    const [movies, setMovies] = useState([]);
    const [searchTerm, setSearchTerm] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const [dataLoaded, setDataLoaded] = useState(false);
    const moviesPerPage = 20;

    useEffect(() => {
        fetchData()
            .then((r) => {
                if (r.status === 200) return r.data;
            })
            .then((data) => {
                setMovies(data);
                setDataLoaded(true);
            });
    }, []);

    const fetchData = async () => {
        return await axios.get(`${API_BASE_URL}ranking`);
    };

    const searchMovies = (value) => {
        setSearchTerm(value);
        setCurrentPage(1);
    };

    const handlePageChange = (pageNumber) => {
        setCurrentPage(pageNumber);
    };

    const filteredMovies = movies.filter((movie) =>
        movie.title.toLowerCase().includes(searchTerm.toLowerCase())
    );

    const indexOfLastMovie = currentPage * moviesPerPage;
    const indexOfFirstMovie = indexOfLastMovie - moviesPerPage;
    const currentMovies = filteredMovies.slice(indexOfFirstMovie, indexOfLastMovie);

    const totalPages = Math.ceil(filteredMovies.length / moviesPerPage);

    return (
        <Page subpage="ranking">
            <div className={style.rankingDiv}>
                {dataLoaded ? (
                    <>
                        <div className={style.headerMain}>
                            <Input type="text" placeholder="Search..." onChange={searchMovies}/>
                            <hr className={style.line}/>
                        </div>
                        <div className={style.movies}>
                            {currentMovies.map((movie, index) => (
                                <MovieTab
                                    key={movie.id}
                                    movieId={movie.id}
                                    number={(currentPage - 1) * moviesPerPage + index + 1}
                                    title={movie.title}
                                    genre={movie.genre}
                                    grade={movie.critic.toFixed(2)}
                                    mark={movie.rate.toFixed(2)}
                                    poster={movie.poster}
                                />
                            ))}
                        </div>
                        <div className={style.pagination}>
                            {Array.from({length: totalPages}, (_, i) => i + 1).map((pageNumber) => (
                                <button
                                    key={pageNumber}
                                    onClick={() => handlePageChange(pageNumber)}
                                    className={pageNumber === currentPage ? style.active : style.normal}
                                >
                                    {pageNumber}
                                </button>
                            ))}
                        </div>
                    </>
                ) : (
                    <Loading/>
                )
                }
            </div>
        </Page>
    );
}

export default Ranking;
