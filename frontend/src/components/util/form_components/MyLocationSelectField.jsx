/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect, useState } from "react";

import {
  Box,
  Checkbox,
  ListSubheader,
  MenuItem,
  Stack,
  TextField,
} from "@mui/material";
import ExpandLess from "@mui/icons-material/ExpandLess";
import ExpandMore from "@mui/icons-material/ExpandMore";

import { useGetLocationsQuery } from "../../location/location/locationsSlice.service";
import { useGetSitesQuery } from "../../location/site/sitesSlice.service";

/**
 * A custom select field component for selecting a location.
 * @param {Object} props - Component props.
 * @param {Object} props.field - Formik field props.
 * @param {Object} props.form - Formik form props.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @param {string} props.label - Label for the select field.
 * @returns {JSX.Element} A select field component.
 */
export default function MyLocationSelectField({
  field,
  form: { touched, errors, setFieldValue },
  setErrorMessage,
  ...props
}) {
  const { data: sites = [], isError: isGetSitesError } = useGetSitesQuery();
  const { data: locations = [], isError: isGetLocationsError } =
    useGetLocationsQuery();

  const [isOpen, setIsOpen] = useState(false);
  const [openSites, setOpenSites] = useState({});

  const selectedLocation =
    locations.find((item) => item.id === field.value)?.name || "";

  /**
   * Handles errors when fetching locations or sites.
   */
  useEffect(() => {
    if (isGetLocationsError) {
      setErrorMessage(
        "An error occurred while loading locations. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
    if (isGetSitesError) {
      setErrorMessage(
        "An error occurred while loading sites. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  /**
   * Handles click on a menu item.
   * @param {string} locationId - ID of the selected location.
   */
  const handleMenuItemClick = (locationId) => {
    setFieldValue(field.name, locationId);
    setIsOpen(false);
  };

  /**
   * Handles click on a site to toggle its visibility.
   * @param {Event} event - Click event.
   * @param {string} siteId - ID of the site.
   */
  const handleSiteClick = (event, siteId) => {
    event.stopPropagation();
    setOpenSites((prevOpenSites) => ({
      ...prevOpenSites,
      [siteId]: !prevOpenSites[siteId],
    }));
  };

  /**
   * Gets locations for a given site.
   * @param {string} siteId - ID of the site.
   * @returns {Array.<Object>} Array of locations.
   */
  const getSiteLocations = (siteId) => {
    return locations.filter((location) => location.siteId === siteId);
  };

  // If locations are not loaded yet, show loading message
  if (locations.length === 0) {
    return (
      <TextField label={props.label + " - Loading..."} disabled></TextField>
    );
  }

  return (
    <TextField
      {...field}
      {...props}
      variant="outlined"
      select
      autoComplete="off"
      SelectProps={{
        renderValue: () => selectedLocation,
        onClose: () => setIsOpen(false),
        open: isOpen,
        onOpen: () => setIsOpen(true),
        value: field.value || "",
        MenuProps: {
          PaperProps: {
            style: {
              maxHeight: 300,
            },
          },
        },
      }}
      error={touched[field.name] && Boolean(errors[field.name])}
      helperText={touched[field.name] && errors[field.name]}
    >
      {sites.map((site, index) => (
        <div className="ml-4" key={index} value={field.value}>
          <ListSubheader className="cursor-pointer hover:bg-gray-100">
            <Stack
              direction="row"
              justifyContent="space-between"
              alignItems="center"
              onClick={(event) => handleSiteClick(event, site.id)}
            >
              {site.name}
              <Box sx={{ flexGrow: 1 }} />
              {openSites[site.id] ? <ExpandLess /> : <ExpandMore />}
            </Stack>
          </ListSubheader>
          {openSites[site.id] &&
            getSiteLocations(site.id).map((location, index) => (
              <div key={index} className="ml-4">
                <MenuItem
                  value={location.id}
                  onClick={() => handleMenuItemClick(location.id)}
                >
                  <Stack
                    direction="row"
                    justifyContent="space-between"
                    alignItems="center"
                    width="100%"
                  >
                    {location.name}
                    <Box sx={{ flexGrow: 1 }} />
                    <Checkbox
                      checked={
                        field.value ? field.value === location.id : false
                      }
                    />
                  </Stack>
                </MenuItem>
              </div>
            ))}
        </div>
      ))}
    </TextField>
  );
}
