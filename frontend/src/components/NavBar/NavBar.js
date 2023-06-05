import React, {useState} from 'react';
import {Link, useLocation, useNavigate} from 'react-router-dom';
import axios from 'axios';
import {useCookies} from 'react-cookie';
import jwt_decode from 'jwt-decode';
import {API_BASE_URL} from '../../index';

import logo from '../../images/logo.svg';

import styles from './NavBar.module.css';

function NavBar() {
    const location = useLocation();
    const navigate = useNavigate();

    const [isDropdownOpen, setDropdownOpen] = useState(false);


    const [cookie, , removeCookie] = useCookies(['jwt']);
    const isRankingPage = location.pathname.endsWith('/ranking');
    const isFollowingPage = location.pathname.endsWith('/following');
    const isPremiersPage = location.pathname.endsWith('/premiers');
    const isSignupPage = location.pathname.endsWith('/signup');
    const isLoginPage = location.pathname.endsWith('/login');

    const handleLogout = () => {
        removeCookie('jwt', {path: '/'});
        navigate('/');
    };

    const viewProfile = async (e) => {
        e.preventDefault();
        const response = await axios.get(`${API_BASE_URL}${jwt_decode(cookie.jwt).username}`);
        const id = response.data;
        navigate('/profile/' + id);
    };

    const handleMouseEnter = () => {
        setDropdownOpen(true);
    };

    const handleMouseLeave = () => {
        setDropdownOpen(false);
    };

    return (
        <div className={styles.navBar}>
            <div className={styles.tabs}>
                <Link to="/">
                    <div className={styles.logoDiv}>
                        <img className={styles.logoImage} src={logo} alt="log"/>
                        <div className={styles.logoText}>
                            <p>Rotten</p>
                            <p>Cabbage</p>
                        </div>
                    </div>
                </Link>
                <div className={styles.menuTabs}>
                    <Link to="/ranking">
                        <p className={isRankingPage ? styles.whiteText : ''}>Rankings</p>
                    </Link>
                    {cookie.jwt && (
                        <Link to="/following">
                            <p className={isFollowingPage ? styles.whiteText : ''}>Following</p>
                        </Link>
                    )}
                    <Link to="/premiers">
                        <p className={isPremiersPage ? styles.whiteText : ''}>Premiers</p>
                    </Link>
                </div>
            </div>
            {isLoginPage || isSignupPage ? (
                <Link to={isLoginPage ? '/signup' : '/login'}>
                    <button className={styles.exitButton}>{isLoginPage ? 'Signup' : 'Login'}</button>
                </Link>
            ) : (
                cookie.jwt ? (
                    <div className={styles.userDiv}>
                        <div
                            className={styles.dropdownContainer}
                            onMouseEnter={handleMouseEnter}
                            onMouseLeave={handleMouseLeave}>
                            <p onClick={viewProfile} className={styles.email}>{jwt_decode(cookie.jwt).username}</p>
                            {isDropdownOpen && (
                                <div className={styles.dropdown}>
                                    <Link to="/settings" className={styles.dropdownOption}>Settings</Link>
                                    <p className={styles.dropdownOption} onClick={handleLogout}>Logout</p>
                                </div>
                            )}
                        </div>
                    </div>
                ) : (
                    <Link to="/login">
                        <button className={styles.exitButton}>Login</button>
                    </Link>
                )
            )}
        </div>
    );
}

export default NavBar;
