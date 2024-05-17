/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Navigate, Route, Routes } from "react-router-dom";

import AuthLayout from "../layouts/AuthLayout";
import GuestLayout from "../layouts/GuestLayout";

import Dashboard from "../pages/Dashboard";
import ForgotPassword from "../pages/ForgotPassword";
import Home from "../pages/Home";
import Login from "../pages/Login";
import Manips from "../pages/Manips";
import Register from "../pages/Register";
import ResetPassword from "../pages/ResetPassword";

/**
 * Root component of the application.
 * @returns {JSX.Element} The JSX representing the root component.
 */
function App() {
  return (
    <div className="bg-slate-100">
      {/* Define the routes for different pages */}
      <Routes>
        {/* Routes accessible for authenticated users */}
        <Route element={<AuthLayout />}>
          {/* Home page */}
          <Route path="/" element={<Home />} />
          {/* Dashboard pages */}
          <Route path="/dashboard/*" element={<Dashboard />} />
          {/* Manipulations pages */}
          <Route path="/my-manips/*" element={<Manips />} />
        </Route>
        {/* Routes accessible for guests/unauthenticated users */}
        <Route element={<GuestLayout />}>
          {/* Login page */}
          <Route path="/login" element={<Login />} />
          {/* Registration page */}
          <Route path="/register" element={<Register />} />
          {/* Forgot password page */}
          <Route path="/forgot-password" element={<ForgotPassword />} />
          {/* Password reset page */}
          <Route path="/password-reset/:token" element={<ResetPassword />} />
        </Route>
        {/* Redirect to the home page if no matching route is found */}
        <Route path="*" element={<Navigate to={"/"} />} />
      </Routes>
    </div>
  );
}

export default App;
