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

import { useGetCategoriesQuery } from "../../equipment/category/categoriesSlice.service";
import { useGetEquipmentGroupsQuery } from "../../equipment/equipment_group/equipmentGroupsSlice.service";

/**
 * A custom select field component for selecting equipment groups.
 * @param {Object} props - Component props.
 * @param {Object} props.field - Formik field props.
 * @param {Object} props.form - Formik form props.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @param {string} props.label - Label for the select field.
 * @returns {JSX.Element} A select field component.
 */
export default function MyEquipmentGroupSelectField({
  field,
  form: { touched, errors, setFieldValue },
  setErrorMessage,
  ...props
}) {
  // Retrieve categories and equipment groups data
  const { data: categories = [], isError: isGetCategoriesError } =
    useGetCategoriesQuery();
  const { data: equipmentGroups = [], isError: isGetEquipmentGroupsError } =
    useGetEquipmentGroupsQuery();

  const [isOpen, setIsOpen] = useState(false);
  const [openCategories, setOpenCategories] = useState({});
  const selectedEquipmentGroup =
    equipmentGroups.find((item) => item.id === field.value)?.name || "";

  /**
   * Handles errors when fetching categories or equipment groups.
   */
  useEffect(() => {
    if (isGetCategoriesError) {
      setErrorMessage(
        "An error occurred while loading categories. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
    if (isGetEquipmentGroupsError) {
      setErrorMessage(
        "An error occurred while loading equipment groups. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  /**
   * Handles click on a menu item.
   * @param {Event} event - Click event.
   * @param {string} equipmentGroupId - ID of the selected equipment group.
   */
  const handleMenuItemClick = (event, equipmentGroupId) => {
    setFieldValue(field.name, equipmentGroupId);
    setIsOpen(false);
  };

  /**
   * Handles click on a category.
   * @param {Event} event - Click event.
   * @param {string} categoryId - ID of the category.
   */
  const handleCategoryClick = (event, categoryId) => {
    event.stopPropagation();
    setOpenCategories((prevOpenCategories) => ({
      ...prevOpenCategories,
      [categoryId]: !prevOpenCategories[categoryId],
    }));
  };

  /**
   * Gets equipment groups for a given category.
   * @param {string} categoryId - ID of the category.
   * @returns {Array} Array of equipment groups.
   */
  const getCategoryEquipmentGroups = (categoryId) => {
    return equipmentGroups.filter(
      (equipmentGroup) => equipmentGroup.categoryId === categoryId
    );
  };

  // If equipment groups are not loaded yet, show loading message
  if (equipmentGroups.length === 0) {
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
        renderValue: () => selectedEquipmentGroup,
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
      {/* Render categories and equipment groups */}
      {categories.map((category, index) => (
        <div className="ml-4" key={index} value={field.value}>
          <ListSubheader className="cursor-pointer hover:bg-gray-100">
            <Stack
              direction="row"
              justifyContent="space-between"
              alignItems="center"
              onClick={(event) => handleCategoryClick(event, category.id)}
            >
              {category.name}
              <Box sx={{ flexGrow: 1 }} />
              {openCategories[category.id] ? <ExpandLess /> : <ExpandMore />}
            </Stack>
          </ListSubheader>
          {openCategories[category.id] &&
            getCategoryEquipmentGroups(category.id).map(
              (equipmentGroup, index) => (
                <div key={index} className="ml-4">
                  <MenuItem
                    value={equipmentGroup.id}
                    onClick={(event) =>
                      handleMenuItemClick(event, equipmentGroup.id)
                    }
                  >
                    <Stack
                      direction="row"
                      justifyContent="space-between"
                      alignItems="center"
                      width="100%"
                    >
                      {equipmentGroup.name}
                      <Box sx={{ flexGrow: 1 }} />
                      <Checkbox
                        checked={
                          field.value
                            ? field.value === equipmentGroup.id
                            : false
                        }
                      />
                    </Stack>
                  </MenuItem>
                </div>
              )
            )}
        </div>
      ))}
    </TextField>
  );
}
