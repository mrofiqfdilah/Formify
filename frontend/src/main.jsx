import React from 'react';
import ReactDOM from 'react-dom/client';
import { createBrowserRouter, RouterProvider } from 'react-router-dom';
import App from './App.jsx';
import Loginpage from './pages/Loginpage.jsx';
import Homepage from './pages/Homepage.jsx';
import './css/bootstrap.css';
import ProtectedRoute from './components/ProtectedRoute';
import './css/style.css';
import './js/bootstrap.js';
import './js/popper.js';
import Createformpage from './pages/Createformpage.jsx';

const router = createBrowserRouter([
  {
    path: "/",
    element: <Loginpage />
  },
  {
    path: "/login",
    element: <Loginpage />
  },
  {
    path: "/home",
    element:  <ProtectedRoute> <Homepage /> </ProtectedRoute>
  },
  {
    path: "/createform",
    element:  <ProtectedRoute> <Createformpage /> </ProtectedRoute>
  },
]);

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <RouterProvider router={router} />
  </React.StrictMode>
);
