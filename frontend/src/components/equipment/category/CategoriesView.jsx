/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Stack, Typography } from "@mui/material";

import CategoriesForm from "./CategoriesForm";
import CategoriesList from "./CategoriesList";
import MyModal from "../../util/MyModal";

/**
 * Functional component for rendering the CategoriesView.
 * @param {Object} props - The component props.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @return {JSX.Element} The rendered component.
 */
const CategoriesView = ({ setErrorMessage }) => {
  // Render the CategoriesView component
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
        <Typography variant="h4">Categories</Typography>
        {/* Modal for adding a new category */}
        <MyModal
          buttonText="Add a new category" // Button text
          title="Add New Category" // Modal title
          formComponent={<CategoriesForm setErrorMessage={setErrorMessage} />} // Form component for adding a new category
        />
      </Stack>
      {/* Rendering the CategoriesList component */}
      <CategoriesList setErrorMessage={setErrorMessage} />
    </>
  );
};

export default CategoriesView;
