/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { apiSlice } from "../../../service/apiSlice.service";

// Extending the apiSlice with additional endpoints
const extendedApiSlice = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    // Query to fetch all equipment
    getEquipments: builder.query({
      query: () => "api/equipments",
      // Function to provide tags for caching
      // eslint-disable-next-line no-unused-vars
      providesTags: (result = [], error, arg) => [
        "Equipment",
        ...result.map(({ id }) => ({ type: "Equipment", id })),
      ],
    }),
    // Query to fetch a single equipment by ID
    getEquipment: builder.query({
      query: (equipmentId) => `api/equipments/${equipmentId}`,
      // Function to provide tags for caching
      providesTags: (result, error, arg) => [{ type: "Equipment", id: arg }],
    }),
    // Mutation to create a new equipment
    createEquipment: builder.mutation({
      query: (equipment) => ({
        url: "api/equipments",
        method: "POST",
        body: equipment,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after creating an equipment
      invalidatesTags: ["Equipment"],
    }),
    // Mutation to update an existing equipment
    updateEquipment: builder.mutation({
      query: (equipment) => ({
        url: `api/equipments/${equipment.id}`,
        method: "PUT",
        body: equipment,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after updating an equipment
      invalidatesTags: (result, error, arg) => [
        { type: "Equipment", id: arg.id },
      ],
    }),
    // Mutation to delete an equipment by ID
    deleteEquipment: builder.mutation({
      query: (equipmentId) => ({
        url: `api/equipments/${equipmentId}`,
        method: "DELETE",
        headers: {
          // Setting headers for CSRF protection
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
        },
      }),
      // Invalidating cache tags after deleting an equipment
      invalidatesTags: ["Equipment"],
    }),
  }),
});

// Destructuring hooks from extendedApiSlice for ease of use
export const {
  useGetEquipmentsQuery,
  useCreateEquipmentMutation,
  useUpdateEquipmentMutation,
  useDeleteEquipmentMutation,
} = extendedApiSlice;
