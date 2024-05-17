/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Box } from "@mui/material";

import ManipsPreferencesView from "../components/manip/ManipsPreferencesView";

/**
 * Component for managing manipulations.
 * @returns {JSX.Element} The JSX representing the manipulations component.
 */
const Manips = () => {
  return (
    <Box className="mx-auto" minHeight="100vh">
      <div>{<ManipsPreferencesView />}</div>
    </Box>
  );
};

export default Manips;
