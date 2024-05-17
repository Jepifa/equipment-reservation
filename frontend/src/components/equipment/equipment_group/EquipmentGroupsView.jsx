/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Stack, Typography } from "@mui/material";

import EquipmentGroupsForm from "./EquipmentGroupsForm";
import EquipmentGroupsList from "./EquipmentGroupsList";
import MyModal from "../../util/MyModal";

/**
 * Functional component for rendering the EquipmentGroupsView.
 * @param {Object} props - The component props.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @return {JSX.Element} The rendered component.
 */
const EquipmentGroupsView = ({ setErrorMessage }) => {
  // Render the EquipmentGroupsView component
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
        <Typography variant="h4">Equipment Groups</Typography>
        {/* Modal for adding a new equipment group */}
        <MyModal
          buttonText="Add a new equipment group" // Button text
          title="Add New Equipment Group" // Modal title
          formComponent={
            <EquipmentGroupsForm setErrorMessage={setErrorMessage} />
          } // Form component for adding a new equipment group
        />
      </Stack>
      {/* Rendering the EquipmentGroup component */}
      <EquipmentGroupsList setErrorMessage={setErrorMessage} />
    </>
  );
};

export default EquipmentGroupsView;
