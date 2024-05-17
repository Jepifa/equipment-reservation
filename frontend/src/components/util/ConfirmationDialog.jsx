/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
} from "@mui/material";

/**
 * Component for rendering a confirmation dialog.
 * @param {Object} props - The component props.
 * @param {boolean} props.open - Indicates whether the dialog is open.
 * @param {Function} props.onClose - The callback function to close the dialog.
 * @param {Function} props.onConfirm - The callback function to confirm the action.
 * @param {string} props.title - The title of the dialog.
 * @param {string} props.message - The message displayed in the dialog.
 * @returns {JSX.Element} - The rendered component.
 */
function ConfirmationDialog({ open, onClose, onConfirm, title, message }) {
  /**
   * Handles the confirmation action.
   */
  const handleConfirm = () => {
    onConfirm();
    onClose();
  };

  return (
    <Dialog open={open} onClose={onClose}>
      <DialogTitle>{title}</DialogTitle>
      <DialogContent>
        <DialogContentText>{message}</DialogContentText>
      </DialogContent>
      <DialogActions>
        <Button onClick={onClose}>Cancel</Button>
        <Button onClick={handleConfirm} variant="contained" color="secondary">
          Confirm
        </Button>
      </DialogActions>
    </Dialog>
  );
}

export default ConfirmationDialog;
