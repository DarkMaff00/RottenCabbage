import React from 'react';
import ReactDOM from 'react-dom/client';
import {createBrowserRouter, RouterProvider} from "react-router-dom";
import './index.css';

import Login from './views/Login/Login';
import Signup from "./views/Signup/Signup";
import Home from './views/Home/Home';

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
]);


const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
    <React.StrictMode>
        <RouterProvider router={router}/>
    </React.StrictMode>
);
