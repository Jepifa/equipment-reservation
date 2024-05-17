/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import React, { useState } from "react";

import { Modal, Box, IconButton, Typography } from "@mui/material";
import AddIcon from "@mui/icons-material/Add";
import ModeEditIcon from "@mui/icons-material/ModeEdit";

/**
 * Component for rendering a modal with edit or add functionality.
 * @param {Object} props - The component props.
 * @param {string} props.buttonText - The text for the button.
 * @param {string} props.title - The title of the modal.
 * @param {JSX.Element} props.formComponent - The form component to be displayed in the modal.
 * @returns {JSX.Element} - The rendered component.
 */
const MyModal = ({ buttonText, title, formComponent }) => {
  const [open, setOpen] = useState(false);

  /**
   * Handles opening the modal.
   */
  const handleOpen = () => {
    setOpen(true);
  };

  /**
   * Handles closing the modal.
   */
  const handleClose = () => {
    setOpen(false);
  };

  return (
    <div>
      {buttonText === "Edit" ? (
        <IconButton aria-label="edit" title={buttonText} onClick={handleOpen}>
          <ModeEditIcon />
        </IconButton>
      ) : (
        <IconButton
          aria-label="add"
          title={buttonText}
          size="large"
          onClick={handleOpen}
        >
          <AddIcon fontSize="inherit" />
        </IconButton>
      )}

      <Modal
        open={open}
        onClose={handleClose}
        aria-labelledby="modal-title"
        aria-describedby="modal-description"
      >
        <Box
          sx={{
            position: "absolute",
            top: "50%",
            left: "50%",
            transform: "translate(-50%, -50%)",
            bgcolor: "background.paper",
            boxShadow: 24,
            p: 4,
            maxWidth: 700,
            width: "90%",
          }}
        >
          <Typography variant="h4" gutterBottom>
            {title}
          </Typography>
          {React.cloneElement(formComponent, { onClose: handleClose })}
        </Box>
      </Modal>
    </div>
  );
};

export default MyModal;
