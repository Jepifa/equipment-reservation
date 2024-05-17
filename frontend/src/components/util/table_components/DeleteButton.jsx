/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useState } from "react";

import { CircularProgress, IconButton, Stack } from "@mui/material";
import DeleteIcon from "@mui/icons-material/Delete";

import ConfirmationDialog from "../ConfirmationDialog";

/**
 * Component for rendering delete button with confirmation dialog.
 * @param {Object} props - The component props.
 * @param {number} props.rowId - The ID of the row.
 * @param {Function} props.deleteRow - The function to delete the row.
 * @returns {JSX.Element} - The rendered component.
 */
const DeleteButton = ({ rowId, deleteRow }) => {
  const [deleteConfirmed, setDeleteConfirmed] = useState(false);
  const [openConfirmation, setOpenConfirmation] = useState(false);
  const [rowIdToDelete, setRowIdToDelete] = useState(null);

  /**
   * Handles the delete action.
   */
  const handleDelete = () => {
    setRowIdToDelete(rowId);
    setOpenConfirmation(true);
  };

  /**
   * Handles the confirmed delete action.
   */
  const handleConfirmDelete = async () => {
    await deleteRow(rowIdToDelete);
    setOpenConfirmation(false);
  };

  return (
    <>
      <Stack alignItems="center" sx={{ justifyContent: "center" }}>
        <IconButton
          aria-label="delete"
          onClick={() => handleDelete(rowId)}
          disabled={deleteConfirmed && rowId === rowIdToDelete}
        >
          {deleteConfirmed ? <CircularProgress size={24} /> : <DeleteIcon />}
        </IconButton>
      </Stack>
      <ConfirmationDialog
        open={openConfirmation}
        onClose={() => setOpenConfirmation(false)}
        onConfirm={() => {
          setDeleteConfirmed(true);
          handleConfirmDelete();
        }}
        title="Confirm Delete"
        message="Are you sure you want to delete this item?"
      />
    </>
  );
};

export default DeleteButton;
