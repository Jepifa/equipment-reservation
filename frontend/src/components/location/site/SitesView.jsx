/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Stack, Typography } from "@mui/material";

import MyModal from "../../util/MyModal";
import SitesForm from "./SitesForm";
import SitesList from "./SitesList";

/**
 * Functional component for rendering the SitesView.
 * @param {Object} props - The props object.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @return {JSX.Element} The rendered component.
 */
const SitesView = ({ setErrorMessage }) => {
  // Render the SitesView component
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
        <Typography variant="h4">Sites</Typography>
        {/* Modal for adding a new site */}
        <MyModal
          buttonText="Add a new site" // Button text
          title="Add New Site" // Modal title
          formComponent={<SitesForm setErrorMessage={setErrorMessage} />} // Form component for adding a new site
        />
      </Stack>
      {/* Rendering the SitesList component */}
      <SitesList setErrorMessage={setErrorMessage} />
    </>
  );
};

export default SitesView;
