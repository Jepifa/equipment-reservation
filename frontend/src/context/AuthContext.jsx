/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { createContext, useContext, useEffect, useState } from "react";

import { useNavigate } from "react-router-dom";

import { useDispatch } from "react-redux";

import {
  useGetCsrfQuery,
  useGetUserQuery,
  useLoginMutation,
  useLogoutMutation,
  useRegisterMutation,
} from "./authSlice.service";

/**
 * Context for authentication.
 */
const AuthContext = createContext({});

/**
 * Provider for the authentication context.
 * @param {Object} props - Component props.
 * @param {JSX.Element} props.children - Child components.
 * @returns {JSX.Element} The JSX representing the authentication provider.
 */
export const AuthProvider = ({ children }) => {
  const { data: user, refetch } = useGetUserQuery();
  const { data: csrf } = useGetCsrfQuery();

  const [loginUser] = useLoginMutation();
  const [logoutUser, { isSuccess, isLoading: isLoggingOut }] =
    useLogoutMutation();
  const [registerUser] = useRegisterMutation();

  const navigate = useNavigate();
  const dispatch = useDispatch();

  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    if (localStorage.getItem("isLoggedIn")) {
      const initializeUser = async () => {
        try {
          setIsLoading(true);
          const userData = await refetch();
          if (userData) {
            localStorage.setItem("isLoggedIn", "true");
          } else {
            localStorage.removeItem("isLoggedIn");
          }
        } catch (error) {
          console.error(error);
        } finally {
          setIsLoading(false);
        }
      };

      initializeUser();
    }
  }, [dispatch, user, refetch]);

  useEffect(() => {
    if (isSuccess) {
      window.location.href = "/login";
    }
  });

  /**
   * Log in user.
   * @param {Object} data - User data.
   */
  const login = async ({ ...data }) => {
    await csrf;
    await loginUser(data).unwrap();
    localStorage.setItem("isLoggedIn", "true");
    navigate("/");
  };

  /**
   * Register user.
   * @param {Object} data - User data.
   */
  const register = async ({ ...data }) => {
    await csrf;
    await registerUser(data).unwrap();
    localStorage.setItem("isLoggedIn", "true");
    navigate("/");
  };

  /**
   * Log out user.
   */
  const logout = () => {
    logoutUser();
    localStorage.removeItem("isLoggedIn");
  };

  /**
   * Check if user has permission.
   * @param {string} permission - Permission to check.
   * @returns {boolean} True if user has permission, false otherwise.
   */
  const can = (permission) => {
    return ((user && user.permissions) || []).find((p) => p === permission)
      ? true
      : false;
  };

  /**
   * Check if user has role.
   * @param {string} role - Role to check.
   * @returns {boolean} True if user has role, false otherwise.
   */
  const hasrole = (role) => {
    return ((user && user.role) || []).find((r) => r === role) ? true : false;
  };

  /**
   * Check if user is validated.
   * @returns {boolean} True if user is validated, false otherwise.
   */
  const validated = () => {
    return user && user.validated ? true : false;
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        login,
        register,
        logout,
        csrf,
        can,
        hasrole,
        validated,
        isLoading,
        refetch,
        isLoggingOut,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

/**
 * Hook for accessing the authentication context.
 * @returns {Object} The authentication context.
 */
// eslint-disable-next-line react-refresh/only-export-components
export default function useAuthContext() {
  return useContext(AuthContext);
}
