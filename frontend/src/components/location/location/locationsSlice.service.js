/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { apiSlice } from "../../../service/apiSlice.service";

// Extending the apiSlice with additional endpoints
const extendedApiSlice = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    // Query to fetch all locations
    getLocations: builder.query({
      query: () => "api/locations",
      // Function to provide tags for caching
      // eslint-disable-next-line no-unused-vars
      providesTags: (result = [], error, arg) => [
        "Location",
        ...result.map(({ id }) => ({ type: "Location", id })),
      ],
    }),
    // Query to fetch a single location by ID
    getLocation: builder.query({
      query: (locationId) => `api/locations/${locationId}`,
      // Function to provide tags for caching
      providesTags: (result, error, arg) => [{ type: "Location", id: arg }],
    }),
    // Mutation to create a new location
    createLocation: builder.mutation({
      query: (location) => ({
        url: "api/locations",
        method: "POST",
        body: location,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after creating a location
      invalidatesTags: ["Location"],
    }),
    // Mutation to update an existing location
    updateLocation: builder.mutation({
      query: (location) => ({
        url: `api/locations/${location.id}`,
        method: "PUT",
        body: location,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after updating a location
      invalidatesTags: (result, error, arg) => [
        { type: "Location", id: arg.id },
      ],
    }),
    // Mutation to delete a location by ID
    deleteLocation: builder.mutation({
      query: (locationId) => ({
        url: `api/locations/${locationId}`,
        method: "DELETE",
        headers: {
          // Setting headers for CSRF protection
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
        },
      }),
      // Invalidating cache tags after deleting a location
      invalidatesTags: ["Location"],
    }),
  }),
});

// Destructuring hooks from extendedApiSlice for ease of use
export const {
  useGetLocationsQuery,
  useCreateLocationMutation,
  useUpdateLocationMutation,
  useDeleteLocationMutation,
} = extendedApiSlice;
