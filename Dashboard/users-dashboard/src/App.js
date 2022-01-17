import React, { useState, useEffect, Fragment } from 'react';
import "./App.css";
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import axios from 'axios';
import Login from './components/Login';
import Dashboard from './components/Dashboard';
import PrivateRoute from './Utils/PrivateRoute';
import { getToken, removeUserSession, setUserSession } from './Utils/Common';

function App() {
  const baseURL = "http://localhost/api/index.php/user/";
  //verifyToken
  const [authLoading, setAuthLoading] = useState(true);

  useEffect(() => {
    const token = getToken();
    if (!token) {
      return;
    }

    axios.get(baseURL + `?token=${token}`).then(response => {
      setUserSession(response.data.token, response.data.user);
      setAuthLoading(false);
    }).catch(error => {
      removeUserSession();
      setAuthLoading(false);
    });
  }, []);

  if (authLoading && getToken()) {
    return <div className="content">Checking Authentication...</div>
  }

  return (
    <div className="wrapper">
      <h1>Live Users Dashboard</h1>
      <BrowserRouter>
        <Routes>
          <Route path='/' element={<PrivateRoute/>}>
            <Route path='/' element={<Dashboard/>}/>
          </Route>
          <Route path='/dashboard' element={<PrivateRoute/>}>
            <Route path='/dashboard' element={<Dashboard/>}/>
          </Route>
          <Route path='/login' element={<Login/>}/>
        </Routes>
    </BrowserRouter>
      
    </div>
  );
}

export default App;