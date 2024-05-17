/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { apiSlice } from "../service/apiSlice.service";

// Extending the apiSlice with additional endpoints
const extendedApiSlice = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    // Query to fetch the current user
    getUser: builder.query({
      query: () => "api/user",
      // Function to provide tags for caching
      providesTags: ["User"],
    }),
    getCsrf: builder.query({
      query: () => "sanctum/csrf-cookie",
    }),
    login: builder.mutation({
      query: (data) => ({
        url: "api/login",
        method: "POST",
        body: data,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            ?.match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      invalidatesTags: ["User"],
    }),
    register: builder.mutation({
      query: (data) => ({
        url: "api/register",
        method: "POST",
        body: data,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      invalidatesTags: ["User"],
    }),
    logout: builder.mutation({
      query: () => ({
        url: "api/logout",
        method: "POST",
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      invalidatesTags: ["User"],
    }),
    forgotPassword: builder.mutation({
      query: (data) => ({
        url: "api/forgot-password",
        method: "POST",
        body: data,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      invalidatesTags: ["User"],
    }),
    resetPassword: builder.mutation({
      query: (data) => ({
        url: "api/reset-password",
        method: "POST",
        body: data,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      invalidatesTags: ["User"],
    }),
  }),
});

// Destructuring hooks from extendedApiSlice for ease of use
export const {
  useGetUserQuery,
  useGetCsrfQuery,
  useLoginMutation,
  useLogoutMutation,
  useRegisterMutation,
  useForgotPasswordMutation,
  useResetPasswordMutation,
} = extendedApiSlice;
