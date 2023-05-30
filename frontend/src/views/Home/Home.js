import React from 'react';
import style from './Home.module.css';
import Page from '../../components/Page/Page';
import camera from '../../images/camera.png';


function Home() {

    return (
        <Page subpage="home">
            <div className={style.home}>
                <div className={style.homeText}>
                    <h1 className={style.homeTitle}>Discover the world of movies and series.</h1>
                    <p className={style.homeDescription}>
                        Search for new movies. Give ratings for what you've watched. See what your friends have been
                        watching and get ready for upcoming premieres. You'll find it all here.
                    </p>
                </div>
                <div className={style.homePicture}>
                    <img className={style.picture} src={camera} alt="homePage" />
                </div>
            </div>
        </Page>
    );
}

export default Home;