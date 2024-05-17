/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { apiSlice } from "../../../service/apiSlice.service";

// Extending the apiSlice with additional endpoints
const extendedApiSlice = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    // Query to fetch all preferences
    getPreferences: builder.query({
      query: () => "api/preferences",
      // Function to provide tags for caching
      // eslint-disable-next-line no-unused-vars
      providesTags: (result = [], error, arg) => [
        "Preference",
        ...result.map(({ id }) => ({ type: "Preference", id })),
      ],
    }),
    // Query to fetch all preferences of a user
    getPreferencesByUser: builder.query({
      query: () => "api/preferences/user",
      // Function to provide tags for caching
      // eslint-disable-next-line no-unused-vars
      providesTags: (result = [], error, arg) => [
        "Preference",
        ...result.map(({ id }) => ({ type: "Preference", id })),
      ],
    }),
    // Query to fetch a single preference by ID
    getPreference: builder.query({
      query: (preferenceId) =>
        preferenceId ? `api/preferences/${preferenceId}` : "null",
      // Function to provide tags for caching
      providesTags: (result, error, arg) => [{ type: "Preference", id: arg }],
    }),
    // Mutation to create a new preference
    createPreference: builder.mutation({
      query: (preference) => ({
        url: "api/preferences",
        method: "POST",
        body: preference,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after creating a preference
      invalidatesTags: ["Preference"],
    }),
    // Mutation to update an existing preference
    updatePreference: builder.mutation({
      query: (preference) => ({
        url: `api/preferences/${preference.id}`,
        method: "PUT",
        body: preference,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after updating a preference
      invalidatesTags: (result, error, arg) => {
        if (!error) {
          return [{ type: "Preference", id: arg.id }];
        } else {
          return [];
        }
      },
    }),
    // Mutation to delete a preference by ID
    deletePreference: builder.mutation({
      query: (preferenceId) => ({
        url: `api/preferences/${preferenceId}`,
        method: "DELETE",
        headers: {
          // Setting headers for CSRF protection
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
        },
      }),
      // Invalidating cache tags after deleting a preference
      invalidatesTags: ["Preference"],
    }),
  }),
});

// Destructuring hooks from extendedApiSlice for ease of use
export const {
  useGetPreferencesQuery,
  useGetPreferenceQuery,
  useGetPreferencesByUserQuery,
  useCreatePreferenceMutation,
  useUpdatePreferenceMutation,
  useDeletePreferenceMutation,
} = extendedApiSlice;
