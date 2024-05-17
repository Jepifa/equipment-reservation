/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useState } from "react";

import { Navigate, Route, Routes } from "react-router-dom";

import {
  Box,
  Typography,
  List,
  ListItemButton,
  ListItemText,
  Collapse,
  Snackbar,
  Alert,
} from "@mui/material";
import ExpandLess from "@mui/icons-material/ExpandLess";
import ExpandMore from "@mui/icons-material/ExpandMore";

import CategoriesView from "../components/equipment/category/CategoriesView";
import EquipmentGroupsView from "../components/equipment/equipment_group/EquipmentGroupsView";
import EquipmentView from "../components/equipment/equipment/EquipmentView";
import LocationsView from "../components/location/location/LocationsView";
import ManipsView from "../components/manip/ManipsView";
import MyNavLink from "../components/util/MyNavLink";
import SitesView from "../components/location/site/SitesView";
import UsersList from "../components/users/UsersList";

/**
 * Component for the dashboard.
 * @returns {JSX.Element} The JSX representing the dashboard component.
 */
const Dashboard = () => {
  const [openEquipment, setOpenEquipment] = useState(false);
  const [openLocations, setOpenLocations] = useState(false);
  const [openManips, setOpenManips] = useState(false);
  const [errorMessage, setErrorMessage] = useState(null);

  /**
   * Handle click on Equipment.
   */
  const handleEquipmentClick = () => {
    setOpenEquipment(!openEquipment);
  };

  /**
   * Handle click on Locations.
   */
  const handleLocationsClick = () => {
    setOpenLocations(!openLocations);
  };

  /**
   * Handle click on Manips.
   */
  const handleManipsClick = () => {
    setOpenManips(!openManips);
  };

  return (
    <Box display="flex" minHeight="100vh">
      {/* Snackbar for displaying error messages */}
      <Snackbar
        open={!!errorMessage}
        autoHideDuration={6000}
        onClose={() => setErrorMessage(null)}
      >
        <Alert severity="error">{errorMessage}</Alert>
      </Snackbar>
      {/* Dashboard menu */}
      <Box width="20%" p={4}>
        <Typography variant="h4" gutterBottom>
          Dashboard
        </Typography>
        <List component="nav">
          <MyNavLink to="/dashboard/users">Users</MyNavLink>
          <ListItemButton onClick={handleManipsClick}>
            <ListItemText primary="Manips" className="p-2" />
            {openManips ? <ExpandLess /> : <ExpandMore />}
          </ListItemButton>
          <Collapse in={openManips} timeout="auto" unmountOnExit>
            <List component="div" disablePadding>
              <MyNavLink to="/dashboard/current-week-manips" sx={{ pl: 4 }}>
                Manips of the week
              </MyNavLink>
              <MyNavLink to="/dashboard/future-manips" sx={{ pl: 4 }}>
                Next week&apos;s manips
              </MyNavLink>
              <MyNavLink to="/dashboard/past-manips" sx={{ pl: 4 }}>
                Past manips
              </MyNavLink>
            </List>
          </Collapse>
          <ListItemButton onClick={handleEquipmentClick}>
            <ListItemText primary="Equipment" className="p-2" />
            {openEquipment ? <ExpandLess /> : <ExpandMore />}
          </ListItemButton>
          <Collapse in={openEquipment} timeout="auto" unmountOnExit>
            <List component="div" disablePadding>
              <MyNavLink to="/dashboard/equipment/equipment" sx={{ pl: 4 }}>
                Equipment
              </MyNavLink>
              <MyNavLink
                to="/dashboard/equipment/equipment-groups"
                sx={{ pl: 4 }}
              >
                Equipment Groups
              </MyNavLink>
              <MyNavLink to="/dashboard/equipment/category" sx={{ pl: 4 }}>
                Category
              </MyNavLink>
            </List>
          </Collapse>
          <ListItemButton onClick={handleLocationsClick}>
            <ListItemText primary="Locations" className="p-2" />
            {openLocations ? <ExpandLess /> : <ExpandMore />}
          </ListItemButton>
          <Collapse in={openLocations} timeout="auto" unmountOnExit>
            <List component="div" disablePadding>
              <MyNavLink to="/dashboard/locations/locations" sx={{ pl: 4 }}>
                Locations
              </MyNavLink>
              <MyNavLink to="/dashboard/locations/sites" sx={{ pl: 4 }}>
                Sites
              </MyNavLink>
            </List>
          </Collapse>
        </List>
      </Box>

      {/* Dashboard content */}
      <Box width="80%" p={4}>
        <ManipsView isAdmin={true} setErrorMessage={setErrorMessage} />
        <Routes>
          <Route path="" element={<Navigate to={"users"} />} />
          <Route
            path="/users"
            element={<UsersList setErrorMessage={setErrorMessage} />}
          />
          <Route
            path="/equipment/equipment"
            element={<EquipmentView setErrorMessage={setErrorMessage} />}
          />
          <Route
            path="/equipment/equipment-groups"
            element={<EquipmentGroupsView setErrorMessage={setErrorMessage} />}
          />
          <Route
            path="/equipment/category"
            element={<CategoriesView setErrorMessage={setErrorMessage} />}
          />
          <Route
            path="/locations/locations"
            element={<LocationsView setErrorMessage={setErrorMessage} />}
          />
          <Route
            path="/locations/sites"
            element={<SitesView setErrorMessage={setErrorMessage} />}
          />
        </Routes>
      </Box>
    </Box>
  );
};

export default Dashboard;
