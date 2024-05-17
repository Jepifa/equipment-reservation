/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { apiSlice } from "../../service/apiSlice.service";

// Extending the apiSlice with additional endpoints
const extendedApiSlice = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    // Query to fetch all manips
    getManips: builder.query({
      query: () => "api/manips",
      // Function to provide tags for caching
      // eslint-disable-next-line no-unused-vars
      providesTags: (result = [], error, arg) => [
        "Manip",
        ...result.map(({ id }) => ({ type: "Manip", id })),
      ],
    }),
    // Query to fetch all manips of a user
    getManipsByUser: builder.query({
      query: () => "api/manips/user",
      // Function to provide tags for caching
      // eslint-disable-next-line no-unused-vars
      providesTags: (result = [], error, arg) => [
        "Manip",
        ...result.map(({ id }) => ({ type: "Manip", id })),
      ],
    }),
    // Query to fetch a single manip by ID
    getManip: builder.query({
      query: (manipId) => (manipId ? `api/manips/${manipId}` : "null"),
      // Function to provide tags for caching
      providesTags: (result, error, arg) => [{ type: "Manip", id: arg }],
    }),
    // Mutation to create a new manip
    createManip: builder.mutation({
      query: (manip) => ({
        url: "api/manips",
        method: "POST",
        body: manip,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after creating a manip
      invalidatesTags: ["Manip"],
    }),
    // Mutation to update an existing manip
    updateManip: builder.mutation({
      query: (manip) => ({
        url: `api/manips/${manip.id}`,
        method: "PUT",
        body: manip,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after updating a manip
      invalidatesTags: (result, error, arg) => {
        if (!error) {
          return [{ type: "Manip", id: arg.id }];
        } else {
          return [];
        }
      },
    }),
    // Mutation to delete a manip by ID
    deleteManip: builder.mutation({
      query: (manipId) => ({
        url: `api/manips/${manipId}`,
        method: "DELETE",
        headers: {
          // Setting headers for CSRF protection
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
        },
      }),
      // Invalidating cache tags after deleting a manip
      invalidatesTags: ["Manip"],
    }),
  }),
});

// Destructuring hooks from extendedApiSlice for ease of use
export const {
  useGetManipsQuery,
  useGetManipQuery,
  useGetManipsByUserQuery,
  useCreateManipMutation,
  useUpdateManipMutation,
  useDeleteManipMutation,
} = extendedApiSlice;
