/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useState } from "react";

import { Button, CircularProgress } from "@mui/material";

/**
 * Component for rendering a submit button with optional loading indicator.
 * @param {Object} props - The component props.
 * @param {boolean} props.isSubmitting - Indicates whether the form is currently submitting.
 * @param {ReactNode} props.children - The content of the button.
 * @returns {JSX.Element} - The rendered component.
 */
const MySubmitButton = ({ isSubmitting, children }) => {
  const [isHovered, setIsHovered] = useState(false);

  const handleMouseEnter = () => {
    setIsHovered(true);
  };

  const handleMouseLeave = () => {
    setIsHovered(false);
  };

  return (
    <div className="flex justify-center">
      <Button
        type="submit"
        variant={isHovered ? "contained" : "outlined"}
        color="primary"
        disabled={isSubmitting}
        onMouseEnter={handleMouseEnter}
        onMouseLeave={handleMouseLeave}
      >
        {isSubmitting ? (
          <>
            {children} <CircularProgress size={16} className="ml-4" />
          </>
        ) : (
          children
        )}
      </Button>
    </div>
  );
};

export default MySubmitButton;
