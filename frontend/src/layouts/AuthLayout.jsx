/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useState } from "react";

import { Link, Navigate, Outlet } from "react-router-dom";

import {
  Alert,
  AppBar,
  Avatar,
  Box,
  Button,
  CircularProgress,
  IconButton,
  List,
  ListItem,
  Menu,
  MenuItem,
  Snackbar,
  Stack,
  Toolbar,
  Typography,
  useTheme,
} from "@mui/material";
import MoreIcon from "@mui/icons-material/MoreVert";

import ColorPicker from "../components/util/ColorPicker";
import LoadingPage from "../components/util/LoadingPage";
import Logo from "/logo.png";
import useAuthContext from "../context/AuthContext";

/**
 * Component for the authenticated layout.
 * @returns {JSX.Element} The JSX representing the authenticated layout component.
 */
const AuthLayout = () => {
  const theme = useTheme();
  const { user, logout, isLoading, hasrole, validated, isLoggingOut } =
    useAuthContext();

  const [anchorEl, setAnchorEl] = useState(null);
  const [errorMessage, setErrorMessage] = useState(null);

  /**
   * Handle opening the menu.
   * @param {Object} event - The event object.
   */
  const handleMenu = (event) => {
    setAnchorEl(event.currentTarget);
  };

  /**
   * Handle closing the menu.
   */
  const handleClose = () => {
    setAnchorEl(null);
  };

  if (localStorage.getItem("isLoggedIn") && isLoading) {
    return <LoadingPage />;
  }

  return user ? (
    <>
      {/* Snackbar for displaying error messages */}
      <Snackbar
        open={!!errorMessage}
        autoHideDuration={6000}
        onClose={() => setErrorMessage(null)}
      >
        <Alert severity="error">{errorMessage}</Alert>
      </Snackbar>
      {/* App bar */}
      <AppBar
        elevation={2}
        sx={{
          backgroundColor: "inherit",
          zIndex: (theme) => theme.zIndex.drawer + 1,
        }}
      >
        <Toolbar>
          <Typography
            variant="h6"
            component="div"
            color="primary"
            sx={{ flexGrow: 1 }}
          >
            <Stack direction="row" alignItems="center">
              <Avatar
                src={Logo}
                alt="Logo"
                sx={{ mr: 1, p: 1, width: 80, height: 80 }}
              />
              Equipment Reservation
            </Stack>
          </Typography>
          {/* Navigation links */}
          <List sx={{ display: "flex", flexDirection: "row", gap: "10px" }}>
            {validated() && (
              <ListItem>
                <Button
                  component={Link}
                  to="/my-manips"
                  variant="text"
                  color="primary"
                  sx={{ whiteSpace: "nowrap" }}
                >
                  My Manips
                </Button>
              </ListItem>
            )}
            <ListItem>
              <Button component={Link} to="/" variant="text" color="primary">
                Home
              </Button>
            </ListItem>
            {validated() && hasrole("admin") && (
              <ListItem>
                <Button
                  component={Link}
                  to="/dashboard"
                  variant="text"
                  color="primary"
                >
                  Dashboard
                </Button>
              </ListItem>
            )}
            {validated() && (
              <ListItem>
                <ColorPicker setErrorMessage={setErrorMessage} />
              </ListItem>
            )}

            {/* User menu */}
            <ListItem>
              <div>
                <IconButton
                  size="large"
                  aria-label="account of current user"
                  aria-controls="menu-appbar"
                  aria-haspopup="true"
                  onClick={handleMenu}
                  color="primary"
                >
                  <MoreIcon />
                </IconButton>
                <Menu
                  id="menu-appbar"
                  anchorEl={anchorEl}
                  anchorOrigin={{
                    vertical: "bottom",
                    horizontal: "right",
                  }}
                  keepMounted
                  transformOrigin={{
                    vertical: "top",
                    horizontal: "right",
                  }}
                  open={Boolean(anchorEl)}
                  onClose={handleClose}
                >
                  <MenuItem
                    onClick={logout}
                    style={{ color: theme.palette.primary.main }}
                  >
                    {isLoggingOut ? (
                      <>
                        {"Logout"}{" "}
                        <CircularProgress size={16} className="ml-4" />
                      </>
                    ) : (
                      "Logout"
                    )}
                  </MenuItem>
                </Menu>
              </div>
            </ListItem>
          </List>
        </Toolbar>
      </AppBar>

      {/* Content */}
      <Box sx={{ paddingTop: (theme) => theme.spacing(12) }}>
        <Outlet />
      </Box>
    </>
  ) : (
    <Navigate to="/login" />
  );
};

export default AuthLayout;
