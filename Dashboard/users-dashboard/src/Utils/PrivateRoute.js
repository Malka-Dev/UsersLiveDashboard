import React from 'react';
import { Route, Navigate, Outlet } from 'react-router-dom';
import { getToken } from './Common';
 
// handle the private routes
function PrivateRoute({ component: Component, ...rest }) {
    const auth = getToken();
    return auth ? <Outlet /> : <Navigate to="/login" />;
}
 
export default PrivateRoute;