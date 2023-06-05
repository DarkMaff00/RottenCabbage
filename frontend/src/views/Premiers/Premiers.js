import React, {useEffect, useState} from 'react';
import style from './Premiers.module.css';
import Page from '../../components/Page/Page';
import MovieTab from "../../components/MovieTab/MovieTab";
import axios from "axios";
import {API_BASE_URL} from "../../index";
import Loading from "../../components/Loading/loading";


function Premiers() {

    const [movies, setMovies] = useState([]);
    const [dataLoaded, setDataLoaded] = useState(false);

    const fetchData = async () => {
        return await axios.get(`${API_BASE_URL}premiers`);
    };

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

    return (
        <Page subpage="premiers">
            {dataLoaded ? (
                <div className={style.premiersDiv}>
                    {movies.map((movie, index) => (
                        <MovieTab
                            key={movie.id}
                            number={index + 1}
                            title={movie.title}
                            poster={movie.poster}
                            trailer={movie.trailerKey}
                            date={movie.release}
                            desc={movie.desc}
                            type="premier"
                        />
                    ))}
                </div>
            ) : (
                <Loading/>
            )
            }
        </Page>
    );
}

export default Premiers;