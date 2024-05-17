/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter } from "react-router-dom";
import { AuthProvider } from "./context/AuthContext.jsx";
import App from "./app/App.jsx";
import "./index.css";
import { Provider } from "react-redux";
import { store } from "./app/store.js";

// Render the application root component
ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    {/* Set up the browser router */}
    <BrowserRouter>
      {/* Provide Redux store */}
      <Provider store={store}>
        {/* Provide authentication context */}
        <AuthProvider>
          {/* Render the main application */}
          <App />
        </AuthProvider>
      </Provider>
    </BrowserRouter>
  </React.StrictMode>
);
