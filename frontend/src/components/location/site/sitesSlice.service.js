/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { apiSlice } from "../../../service/apiSlice.service";

// Extending the apiSlice with additional endpoints
const extendedApiSlice = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    // Query to fetch all sites
    getSites: builder.query({
      query: () => "api/sites",
      // Function to provide tags for caching
      // eslint-disable-next-line no-unused-vars
      providesTags: (result = [], error, arg) => [
        "Site",
        ...result.map(({ id }) => ({ type: "Site", id })),
      ],
    }),
    // Query to fetch a single site by ID
    getSite: builder.query({
      query: (siteId) => `api/sites/${siteId}`,
      // Function to provide tags for caching
      providesTags: (result, error, arg) => [{ type: "Site", id: arg }],
    }),
    // Mutation to create a new site
    createSite: builder.mutation({
      query: (site) => ({
        url: "api/sites",
        method: "POST",
        body: site,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after creating a site
      invalidatesTags: ["Site"],
    }),
    // Mutation to update an existing site
    updateSite: builder.mutation({
      query: (site) => ({
        url: `api/sites/${site.id}`,
        method: "PUT",
        body: site,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after updating a site
      invalidatesTags: (result, error, arg) => [{ type: "Site", id: arg.id }],
    }),
    // Mutation to delete a site by ID
    deleteSite: builder.mutation({
      query: (id) => ({
        url: `api/sites/${id}`,
        method: "DELETE",
        headers: {
          // Setting headers for CSRF protection
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
        },
      }),
      // Invalidating cache tags after deleting a site
      invalidatesTags: ["Site"],
    }),
  }),
});

// Destructuring hooks from extendedApiSlice for ease of use
export const {
  useGetSitesQuery,
  useCreateSiteMutation,
  useUpdateSiteMutation,
  useDeleteSiteMutation,
} = extendedApiSlice;
