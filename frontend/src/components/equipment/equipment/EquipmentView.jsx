/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Stack, Typography } from "@mui/material";

import EquipmentForm from "./EquipmentForm";
import EquipmentList from "./EquipmentList";
import MyModal from "../../util/MyModal";

/**
 * Functional component for rendering the EquipmentView.
 * @param {Object} props - The component props.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @return {JSX.Element} The rendered component.
 */
const EquipmentView = ({ setErrorMessage }) => {
  // Render the EquipmentView component
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
        <Typography variant="h4">Equipment</Typography>
        {/* Modal for adding a new equipment group */}
        <MyModal
          buttonText="Add a new equipment" // Button text
          title="Add New Equipment" // Modal title
          formComponent={<EquipmentForm setErrorMessage={setErrorMessage} />} // Form component for adding a new equipment group
        />
      </Stack>
      {/* Rendering the EquipmentGroup component */}
      <EquipmentList setErrorMessage={setErrorMessage} />
    </>
  );
};

export default EquipmentView;
