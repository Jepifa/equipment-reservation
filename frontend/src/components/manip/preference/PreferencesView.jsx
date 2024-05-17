/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Stack, Typography } from "@mui/material";

import MyModal from "../../util/MyModal";
import PreferencesForm from "./PreferencesForm";
import PreferencesList from "./PreferencesList";

/**
 * Functional component for rendering the PreferencesView.
 * @param {Object} props - The props object.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @return {JSX.Element} The rendered component.
 */
const PreferencesView = ({ setErrorMessage }) => {
  // Render the PreferencesView component
  return (
    <>
      {/* Stack component for layout */}
      <Stack direction="row" justifyContent="space-between" alignItems="center">
        {/* Heading */}
        <Typography
          variant="h4"
          component="h4"
          className="font-bold"
          sx={{ marginY: 2 }}
        >
          Preferences
        </Typography>
        {/* Modal for adding a new preference */}
        <MyModal
          buttonText="Add a new preference" // Button text
          title="Add New Preference" // Modal title
          formComponent={<PreferencesForm setErrorMessage={setErrorMessage} />} // Form component for adding a new preference
        />
      </Stack>
      {/* Rendering the PreferencesList component */}

      <PreferencesList setErrorMessage={setErrorMessage} />
    </>
  );
};

export default PreferencesView;
