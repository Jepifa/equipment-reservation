/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { apiSlice } from "../../../service/apiSlice.service";

// Extending the apiSlice with additional endpoints
const extendedApiSlice = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    // Query to fetch all categories
    getCategories: builder.query({
      query: () => "api/categories",
      // Function to provide tags for caching
      // eslint-disable-next-line no-unused-vars
      providesTags: (result = [], error, arg) => [
        "Category",
        ...result.map(({ id }) => ({ type: "Category", id })),
      ],
    }),
    // Query to fetch a single category by ID
    getCategory: builder.query({
      query: (categoryId) => `api/categories/${categoryId}`,
      // Function to provide tags for caching
      providesTags: (result, error, arg) => [{ type: "Category", id: arg }],
    }),
    // Mutation to create a new category
    createCategory: builder.mutation({
      query: (category) => ({
        url: "api/categories",
        method: "POST",
        body: category,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after creating a category
      invalidatesTags: ["Category"],
    }),
    // Mutation to update an existing category
    updateCategory: builder.mutation({
      query: (category) => ({
        url: `api/categories/${category.id}`,
        method: "PUT",
        body: category,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after updating a category
      invalidatesTags: (result, error, arg) => [
        { type: "Category", id: arg.id },
      ],
    }),
    // Mutation to delete a category by ID
    deleteCategory: builder.mutation({
      query: (id) => ({
        url: `api/categories/${id}`,
        method: "DELETE",
        headers: {
          // Setting headers for CSRF protection
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
        },
      }),
      // Invalidating cache tags after deleting a category
      invalidatesTags: ["Category"],
    }),
  }),
});

// Destructuring hooks from extendedApiSlice for ease of use
export const {
  useGetCategoriesQuery,
  useCreateCategoryMutation,
  useUpdateCategoryMutation,
  useDeleteCategoryMutation,
} = extendedApiSlice;
