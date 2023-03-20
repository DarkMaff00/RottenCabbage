import React from 'react';
import ReactDOM from 'react-dom/client';
import {createBrowserRouter, RouterProvider} from "react-router-dom";
import './index.css';

import Login from './views/Login/Login';
import Signup from "./views/Signup/Signup";
import Home from './views/Home/Home';
import Ranking from "./views/Ranking/Ranking";
import Following from "./views/Following/Following";

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
]);


const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
    <React.StrictMode>
        <RouterProvider router={router}/>
    </React.StrictMode>
);
