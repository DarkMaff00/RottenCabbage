import React from 'react';
import ReactDOM from 'react-dom/client';
import {createBrowserRouter, RouterProvider} from "react-router-dom";
import './index.css';

import Login from './views/Login/Login';
import Signup from "./views/Signup/Signup";
import Home from './views/Home/Home';
import Ranking from "./views/Ranking/Ranking";
import Following from "./views/Following/Following";
import Premiers from "./views/Premiers/Premiers";
import Profile from "./views/Profile/Profile";
import MovieInfo from "./views/MovieInfo/MovieInfo";
import Settings from "./views/Settings/Settings";
import ChangePassword from "./views/ChangePassword/ChangePassword";
import DeleteAccount from "./views/DeleteAccount/DeleteAccount";
import AddMovie from "./views/addMovie/addMovie";

const router = createBrowserRouter([
    {
        path: '/login',
        element: <Login/>,
        errorElement: <Home/>
    },
    {
        path: '/signup',
        element: <Signup/>,
        errorElement: <Home/>
    },
    {
        path: '/',
        element: <Home/>,
        errorElement: <div>404</div>
    },
    {
        path: '/ranking',
        element: <Ranking/>,
        errorElement: <Home/>
    },
    {
        path: '/following',
        element: <Following/>,
        errorElement: <Home/>
    },
    {
        path: '/premiers',
        element: <Premiers/>,
        errorElement: <Home/>
    },
    {
        path: '/profile',
        element: <Profile/>,
        errorElement: <Home/>
    },
    {
        path: '/movieInfo',
        element: <MovieInfo/>,
        errorElement: <Home/>
    },
    {
        path: '/settings',
        element: <Settings/>,
        errorElement: <Home/>
    },
    {
        path: '/changePassword',
        element: <ChangePassword/>,
        errorElement: <Home/>
    },
    {
        path: '/deleteAccount',
        element: <DeleteAccount/>,
        errorElement: <Home/>
    },
    {
        path: '/addMovie',
        element: <AddMovie/>,
        errorElement: <Home/>
    },
]);


const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
    <React.StrictMode>
        <RouterProvider router={router}/>
    </React.StrictMode>
);
