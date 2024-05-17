/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { IconButton, Stack } from "@mui/material";
import ModeEditIcon from "@mui/icons-material/ModeEdit";

/**
 * Component for rendering edit button.
 * @param {Object} props - The component props.
 * @param {number} props.rowId - The ID of the row.
 * @param {Function} props.deleteRow - The function to update the row.
 * @returns {JSX.Element} - The rendered component.
 */
const EditButton = ({ rowId, updateRow }) => {
  /**
   * Handles the update action.
   */
  const handleUpdate = () => {
    updateRow(rowId);
  };

  return (
    <>
      <Stack alignItems="center" sx={{ justifyContent: "center" }}>
        <IconButton aria-label="delete" onClick={handleUpdate}>
          <ModeEditIcon />
        </IconButton>
      </Stack>
    </>
  );
};

export default EditButton;
