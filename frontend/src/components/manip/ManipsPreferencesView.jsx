/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useState } from "react";
import { Route, Routes } from "react-router-dom";

import { Alert, Box, List, Snackbar, Typography } from "@mui/material";

import ManipsView from "./ManipsView";
import MyNavLink from "../util/MyNavLink";
import PreferencesView from "./preference/PreferencesView";

/**
 * ManipsPreferencesView component to display the dashboard view for manips and preferences.
 * @param {Object} props - The props object.
 * @param {boolean} props.isAdmin - Indicates whether the user is an admin.
 * @returns {JSX.Element} - The ManipsPreferencesView component.
 */
const ManipsPreferencesView = ({ isAdmin }) => {
  const [errorMessage, setErrorMessage] = useState(null);

  // Render the ManipsPreferencesView component
  return (
    <>
      <Snackbar
        open={!!errorMessage}
        autoHideDuration={6000}
        onClose={() => setErrorMessage(null)}
      >
        <Alert severity="error">{errorMessage}</Alert>
      </Snackbar>
      <Box display="flex" minHeight="100vh">
        {/* Dashboard menu */}
        <Box width="20%" p={4}>
          <Typography variant="h4" gutterBottom>
            Manips
          </Typography>
          <List component="nav">
            <MyNavLink to="/my-manips/current-week-manips">
              Manips of the week
            </MyNavLink>
            <MyNavLink to="/my-manips/future-manips">
              Next week&apos;s manips
            </MyNavLink>
            <MyNavLink to="/my-manips/past-manips">Past manips</MyNavLink>
            <MyNavLink to="/my-manips/preferences">Preferences</MyNavLink>
          </List>
        </Box>

        {/* Dashboard content */}
        <Box width="80%" p={4}>
          <ManipsView isAdmin={isAdmin} setErrorMessage={setErrorMessage} />
          <Routes>
            <Route
              path="/preferences"
              element={<PreferencesView setErrorMessage={setErrorMessage} />}
            />
          </Routes>
        </Box>
      </Box>
    </>
  );
};

export default ManipsPreferencesView;
