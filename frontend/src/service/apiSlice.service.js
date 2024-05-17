/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";

// Create an API slice using Redux Toolkit's createApi function
export const apiSlice = createApi({
  // Specify the reducer path
  reducerPath: "equipmentReservationApi",
  // Define the base query configuration
  baseQuery: fetchBaseQuery({
    // Set the base URL for API requests
    baseUrl: "http://localhost:8000/",
    // Include credentials in requests
    credentials: "include",
  }),
  // Define API endpoints (currently empty)
  endpoints: () => ({}),
});
