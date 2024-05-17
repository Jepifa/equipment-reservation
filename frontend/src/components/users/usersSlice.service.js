/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { apiSlice } from "../../service/apiSlice.service";

// Extending the apiSlice with additional endpoints
const extendedApiSlice = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    // Query to fetch all users
    getUsers: builder.query({
      query: () => "api/users",
      // Function to provide tags for caching
      // eslint-disable-next-line no-unused-vars
      providesTags: (result = [], error, arg) => [
        "User",
        ...result.map(({ id }) => ({ type: "User", id })),
      ],
    }),
    // Query to fetch other users
    getOtherUsers: builder.query({
      query: () => "api/users/other-users",
      // Function to provide tags for caching
      // eslint-disable-next-line no-unused-vars
      providesTags: (result = [], error, arg) => [
        "User",
        ...result.map(({ id }) => ({ type: "User", id })),
      ],
    }),
    // Query to fetch a single user by ID
    getUser: builder.query({
      query: (userId) => `api/users/${userId}`,
      // Function to provide tags for caching
      providesTags: (result, error, arg) => [{ type: "User", id: arg }],
    }),
    // Mutation to create a new user
    createUser: builder.mutation({
      query: (user) => ({
        url: "api/users",
        method: "POST",
        body: user,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after creating a user
      invalidatesTags: ["User"],
    }),
    // Mutation to update an existing user
    updateUser: builder.mutation({
      query: (user) => ({
        url: `api/users/${user.id}`,
        method: "PUT",
        body: user,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after updating a user
      invalidatesTags: (result, error, arg) => [{ type: "User", id: arg.id }],
    }),
    // Mutation to delete a user by ID
    deleteUser: builder.mutation({
      query: (id) => ({
        url: `api/users/${id}`,
        method: "DELETE",
        headers: {
          // Setting headers for CSRF protection
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
        },
      }),
      // Invalidating cache tags after deleting a user
      invalidatesTags: ["User"],
    }),
    // Mutation change color of a user by ID
    changeColor: builder.mutation({
      query: ({ id, color }) => ({
        url: `api/users/${id}/change-color/${color}`,
        method: "PUT",
        headers: {
          // Setting headers for CSRF protection
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
        },
      }),
      invalidatesTags: ["User"],
    }),
  }),
});

// Destructuring hooks from extendedApiSlice for ease of use
export const {
  useGetUsersQuery,
  useGetOtherUsersQuery,
  useCreateUserMutation,
  useUpdateUserMutation,
  useDeleteUserMutation,
  useChangeColorMutation,
} = extendedApiSlice;
