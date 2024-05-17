/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Navigate, Outlet } from "react-router-dom";

import useAuthContext from "../context/AuthContext";

/**
 * Component for the guest layout.
 * @returns {JSX.Element} The JSX representing the guest layout component.
 */
const GuestLayout = () => {
  const { user } = useAuthContext();

  return !user ? (
    <>
      {/* Render children components if the user is not authenticated */}
      <section className="h-screen w-screen">
        <Outlet />
      </section>
    </>
  ) : (
    // Redirect to the home page if the user is authenticated
    <Navigate to="/" />
  );
};

export default GuestLayout;
