import style from './NavBar.module.css';
import React, {useState} from "react";
import {Link, useLocation, useNavigate} from "react-router-dom";
import logo from "../../images/logo.svg";
import userArrow from '../../images/user-arrow.svg';
import {useCookies} from "react-cookie";
import jwt_decode from "jwt-decode";
import axios from "axios";
import {API_BASE_URL} from "../../index";

function NavBar() {
    const location = useLocation();
    const navigate = useNavigate();

    const [isDropdownOpen, setDropdownOpen] = useState(false);
    const [isArrowRotated, setArrowRotated] = useState(false);

    const [cookie, setCookie, removeCookie] = useCookies(['jwt']);
    const isRankingPage = location.pathname.endsWith('/ranking');
    const isFollowingPage = location.pathname.endsWith('/following');
    const isPremiersPage = location.pathname.endsWith('/premiers');
    const isSignupPage = location.pathname.endsWith('/signup');
    const isLoginPage = location.pathname.endsWith('/login');

    const handleLogout = () => {
        removeCookie('jwt',{path:'/'});
        navigate('/');
    };

    const viewProfile = async (e) => {
        e.preventDefault();
        const response = await axios.get(`${API_BASE_URL}${jwt_decode(cookie.jwt)['username']}`);
        const id = response.data
        navigate('/profile/' + id);
    };

    const handleDropdownToggle = () => {
        setDropdownOpen(!isDropdownOpen);
        setArrowRotated(!isArrowRotated);
    };

    return (
        <div className={style.changeInput}>
            <div className={style.tabs}>
                <Link to='/'>
                    <div className={style.logoDiv}>
                        <img className={style.logoImage} src={logo} alt="log"/>
                        <div className={style.logoText}>
                            <p>Rotten</p>
                            <p>Cabbage</p>
                        </div>
                    </div>
                </Link>
                <div className={style.menuTabs}>
                    <Link to='/ranking'>
                        <p className={isRankingPage ? style.whiteText : ''}>Rankings</p>
                    </Link>
                    <Link to='/following'>
                        <p className={isFollowingPage ? style.whiteText : ''}>Following</p>
                    </Link>
                    <Link to='/premiers'>
                        <p className={isPremiersPage ? style.whiteText : ''}>Premiers</p>
                    </Link>
                </div>
            </div>
            {isLoginPage || isSignupPage ? (
                <Link to={isLoginPage ? '/signup' : '/login'}>
                    <button className={style.exitButton}>{isLoginPage ? 'Signup' : 'Login'}</button>
                </Link>
            ) : (
                cookie.jwt ? (
                    <div className={style.userDiv}>
                        <div className={style.dropdownContainer}>
                            <p onClick={viewProfile} className={style.email}>{jwt_decode(cookie.jwt)['username']}</p>
                            {isDropdownOpen && (
                                <div className={`${style.dropdown}`}>
                                    <Link to="/settings" className={style.dropdownOption}>Settings</Link>
                                    <p className={style.dropdownOption} onClick={handleLogout}>Logout</p>
                                </div>
                            )}
                        </div>
                        <img
                            src={userArrow}
                            alt="userArrow"
                            className={`${isArrowRotated ? style.rotated : ''}`}
                            onClick={handleDropdownToggle}
                        />
                    </div>
                ) : (
                    <Link to="/login">
                        <button className={style.exitButton}>Login</button>
                    </Link>
                )
            )}
        </div>
    );
}

export default NavBar;