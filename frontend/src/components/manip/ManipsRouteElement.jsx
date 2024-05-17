/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Stack, Typography } from "@mui/material";

import ManipsForm from "./manips-form/ManipsForm";
import ManipsList from "./ManipsList";
import MyModal from "../util/MyModal";

/**
 * ManipsRouteElement component to display a section of manips within a route.
 * @param {Object} props - The props object.
 * @param {boolean} props.isAdmin - Indicates whether the user is an admin.
 * @param {Array} props.manips - The array of manips to display.
 * @param {boolean} props.isFetching - Indicates whether data is currently being fetched.
 * @param {boolean} props.isLoading - Indicates whether data is currently being loaded.
 * @param {string} props.title - The title of the section.
 * @param {function} props.setErrorMessage - Function to set error messages.
 * @returns {JSX.Element} - The ManipsRouteElement component.
 */
const ManipsRouteElement = ({
  isAdmin,
  manips,
  isFetching,
  isLoading,
  title,
  setErrorMessage,
}) => {
  return (
    <>
      <Stack
        direction="row"
        justifyContent="space-between"
        alignItems="center"
        mt={2} // Margin top
        mb={2} // Margin bottom
      >
        {/* Heading */}
        <Typography variant="h4">{title}</Typography>
        {/* Modal for adding a new manip */}
        <MyModal
          buttonText="Add a new manip" // Button text
          title="Add New Manip" // Modal title
          formComponent={
            <ManipsForm isAdmin={isAdmin} setErrorMessage={setErrorMessage} />
          } // Form component for adding a new manip
        />
      </Stack>

      <ManipsList
        manips={manips}
        isLoading={isLoading}
        isFetching={isFetching}
        isAdmin={isAdmin}
        setErrorMessage={setErrorMessage}
      />
    </>
  );
};

export default ManipsRouteElement;
