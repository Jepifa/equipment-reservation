/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { NavLink, useLocation } from "react-router-dom";

import { ListItemButton, ListItemText, Typography } from "@mui/material";

/**
 * A custom navigation link component.
 * @param {Object} props - Component props.
 * @param {string} props.to - The target location for the link.
 * @param {ReactNode} props.children - The content of the link.
 * @param {Object} props.sx - Additional styles for the ListItemButton component.
 * @returns {JSX.Element} Navigation link component.
 */
const MyNavLink = ({ to, children, sx }) => {
  const location = useLocation();

  const isActive = location.pathname === to;
  const className = `block p-2 ${
    isActive ? "bg-blue-100 rounded-md text-blue-500" : ""
  }`;

  return (
    <NavLink to={to} style={{ textDecoration: "none" }} className="block">
      <ListItemButton sx={sx}>
        <ListItemText
          primary={
            <Typography style={{ fontWeight: "bold" }}>{children}</Typography>
          }
          className={className}
        />
      </ListItemButton>
    </NavLink>
  );
};
export default MyNavLink;
