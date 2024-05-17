/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Stack } from "@mui/material";

import DeleteButton from "./DeleteButton";
import MyModal from "../MyModal";

/**
 * Component for rendering edit and delete buttons with confirmation dialog.
 * @param {Object} props - The component props.
 * @param {number} props.rowId - The ID of the row.
 * @param {Function} props.deleteRow - The function to delete the row.
 * @param {JSX.Element} props.formComponent - The form component for editing the row.
 * @returns {JSX.Element} - The rendered component.
 */
const EditDeleteButtons = ({ rowId, deleteRow, formComponent }) => {
  return (
    <>
      <Stack
        direction="row"
        spacing={2}
        alignItems="center"
        sx={{ justifyContent: "center" }}
      >
        <DeleteButton rowId={rowId} deleteRow={deleteRow} />
        <MyModal buttonText="Edit" title="Edit" formComponent={formComponent} />
      </Stack>
    </>
  );
};

export default EditDeleteButtons;
