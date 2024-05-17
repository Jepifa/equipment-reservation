/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { apiSlice } from "../../../service/apiSlice.service";

// Extending the apiSlice with additional endpoints
const extendedApiSlice = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    // Query to fetch all equipment groups
    getEquipmentGroups: builder.query({
      query: () => "api/equipment-groups",
      // Function to provide tags for caching
      // eslint-disable-next-line no-unused-vars
      providesTags: (result = [], error, arg) => [
        "EquipmentGroup",
        ...result.map(({ id }) => ({ type: "EquipmentGroup", id })),
      ],
    }),
    // Query to fetch a single equipment group by ID
    getEquipmentGroup: builder.query({
      query: (equipmentGroupId) => `api/equipment-groups/${equipmentGroupId}`,
      // Function to provide tags for caching
      providesTags: (result, error, arg) => [
        { type: "EquipmentGroup", id: arg },
      ],
    }),
    // Mutation to create a new equipment group
    createEquipmentGroup: builder.mutation({
      query: (equipmentGroup) => ({
        url: "api/equipment-groups",
        method: "POST",
        body: equipmentGroup,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after creating an equipment group
      invalidatesTags: ["EquipmentGroup"],
    }),
    // Mutation to update an existing equipment group
    updateEquipmentGroup: builder.mutation({
      query: (equipmentGroup) => ({
        url: `api/equipment-groups/${equipmentGroup.id}`,
        method: "PUT",
        body: equipmentGroup,
        headers: {
          // Setting headers for CSRF protection and content type
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
          accept: "application/json, text/plain, */*",
        },
      }),
      // Invalidating cache tags after updating an equipment group
      invalidatesTags: (result, error, arg) => [
        { type: "EquipmentGroup", id: arg.id },
      ],
    }),
    // Mutation to delete an equipment group by ID
    deleteEquipmentGroup: builder.mutation({
      query: (equipmentGroupId) => ({
        url: `api/equipment-groups/${equipmentGroupId}`,
        method: "DELETE",
        headers: {
          // Setting headers for CSRF protection
          "X-XSRF-TOKEN": document.cookie
            .match(/XSRF-TOKEN=([^;]+)/)[1]
            .slice(0, -3),
        },
      }),
      // Invalidating cache tags after deleting an equipment group
      invalidatesTags: ["EquipmentGroup"],
    }),
  }),
});

// Destructuring hooks from extendedApiSlice for ease of use
export const {
  useGetEquipmentGroupsQuery,
  useCreateEquipmentGroupMutation,
  useUpdateEquipmentGroupMutation,
  useDeleteEquipmentGroupMutation,
} = extendedApiSlice;
