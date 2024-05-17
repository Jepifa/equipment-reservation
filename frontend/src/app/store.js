/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { combineReducers, configureStore } from "@reduxjs/toolkit";
import { setupListeners } from "@reduxjs/toolkit/query";

import { apiSlice } from "../service/apiSlice.service";

/**
 * Configure and create the Redux store.
 * @returns {Object} The Redux store.
 */
export const store = configureStore({
  // Define the initial state of the store
  preloadedState: {},
  // Combine reducers, including the API slice reducer
  reducer: combineReducers({
    [apiSlice.reducerPath]: apiSlice.reducer,
  }),
  // Configure middleware, including API slice middleware
  middleware: (getDefaultMiddleware) =>
    getDefaultMiddleware().concat(apiSlice.middleware),
});

// Set up listeners for query actions
setupListeners(store.dispatch);
