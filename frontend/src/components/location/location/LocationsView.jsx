/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Stack, Typography } from "@mui/material";

import LocationsForm from "./LocationsForm";
import LocationsList from "./LocationsList";
import MyModal from "../../util/MyModal";

/**
 * Functional component for rendering the LocationsView.
 * @param {Object} props - The props object.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @return {JSX.Element} The rendered component.
 */
const LocationsView = ({ setErrorMessage }) => {
  // Render the LocationsView component
  return (
    <>
      {/* Stack component for layout */}
      <Stack
        direction="row"
        justifyContent="space-between"
        alignItems="center"
        mt={2} // Margin top
        mb={2} // Margin bottom
      >
        {/* Heading */}
        <Typography variant="h4">Locations</Typography>
        {/* Modal for adding a new location */}
        <MyModal
          buttonText="Add a new location" // Button text
          title="Add New Location" // Modal title
          formComponent={<LocationsForm setErrorMessage={setErrorMessage} />} // Form component for adding a new location
        />
      </Stack>
      {/* Rendering the LocationsList component */}
      <LocationsList setErrorMessage={setErrorMessage} />
    </>
  );
};

export default LocationsView;
