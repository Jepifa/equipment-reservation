/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect, useState } from "react";

import {
  Box,
  Checkbox,
  Chip,
  ListSubheader,
  MenuItem,
  OutlinedInput,
  Stack,
  TextField,
} from "@mui/material";
import ExpandLess from "@mui/icons-material/ExpandLess";
import ExpandMore from "@mui/icons-material/ExpandMore";

import { useGetCategoriesQuery } from "../../equipment/category/categoriesSlice.service";
import { useGetEquipmentGroupsQuery } from "../../equipment/equipment_group/equipmentGroupsSlice.service";
import { useGetEquipmentsQuery } from "../../equipment/equipment/equipmentSlice.service";

/**
 * A custom select field component for selecting equipment.
 * @param {Object} props - Component props.
 * @param {Object} props.field - Formik field props.
 * @param {Object} props.form - Formik form props.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @param {string} props.label - Label for the select field.
 * @returns {JSX.Element} A select field component.
 */
export default function MyEquipmentSelectField({
  field,
  form: { touched, errors, setFieldValue },
  setErrorMessage,
  ...props
}) {
  const { data: categories = [], isError: isGetCategoriesError } =
    useGetCategoriesQuery();
  const { data: equipmentGroups = [], isError: isGetEquipmentGroupsError } =
    useGetEquipmentGroupsQuery();
  const { data: equipments = [], isError: isGetEquipmentsError } =
    useGetEquipmentsQuery();

  const [openCategories, setOpenCategories] = useState({});
  const [openEquipmentGroups, setOpenEquipmentGroups] = useState({});

  /**
   * Retrieves names of selected equipments.
   */
  const selectedEquipments = field.value.map((selectedId) => {
    const selectedItem = equipments.find((item) => item.id === selectedId);
    return selectedItem ? selectedItem.name : "";
  });

  /**
   * Handles errors when fetching categories, equipment groups, or equipments.
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
    if (isGetEquipmentsError) {
      setErrorMessage(
        "An error occurred while loading equipment. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  /**
   * Handles click on a menu item to select or deselect an equipment.
   * @param {Event} event - Click event.
   * @param {string} equipmentId - ID of the selected equipment.
   */
  const handleMenuItemClick = (event, equipmentId) => {
    event.stopPropagation();

    const index = field.value.indexOf(equipmentId);

    if (index === -1) {
      setFieldValue(field.name, [...field.value, equipmentId]);
    } else {
      const updatedValue = [...field.value];
      updatedValue.splice(index, 1);
      setFieldValue(field.name, updatedValue);
    }
  };

  /**
   * Handles click on a category to toggle its open state.
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
   * Handles click on an equipment group to toggle its visibility.
   * @param {Event} event - Click event.
   * @param {string} groupId - ID of the equipment group.
   */
  const handleEquipmentGroupClick = (event, groupId) => {
    event.stopPropagation();
    setOpenEquipmentGroups((prevOpenEquipmentGroups) => ({
      ...prevOpenEquipmentGroups,
      [groupId]: !prevOpenEquipmentGroups[groupId],
    }));
  };

  const getCategoryEquipmentGroups = (categoryId) => {
    return equipmentGroups.filter((group) => group.categoryId === categoryId);
  };

  const getEquipmentGroupEquipments = (groupId) => {
    return equipments.filter(
      (equipment) => equipment.equipmentGroupId === groupId
    );
  };

  if (equipments.length === 0) {
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
        multiple: true,
        input: <OutlinedInput id="select-multiple-chip" label={props.label} />,
        renderValue: () => (
          <Box sx={{ display: "flex", flexWrap: "wrap", gap: 0.5 }}>
            {selectedEquipments.map((name, index) => (
              <Chip key={index} label={name} />
            ))}
          </Box>
        ),
        value: field.value || [],
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
      {categories.map((category, index) => (
        <div key={index} value={field.value}>
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
            getCategoryEquipmentGroups(category.id).map((group, index) => (
              <div className="ml-4" key={index}>
                <ListSubheader className="cursor-pointer hover:bg-gray-100">
                  <Stack
                    direction="row"
                    justifyContent="space-between"
                    alignItems="center"
                    onClick={(event) =>
                      handleEquipmentGroupClick(event, group.id)
                    }
                  >
                    {group.name}
                    <Box sx={{ flexGrow: 1 }} />
                    {openEquipmentGroups[group.id] ? (
                      <ExpandLess />
                    ) : (
                      <ExpandMore />
                    )}
                  </Stack>
                </ListSubheader>
                {openEquipmentGroups[group.id] &&
                  getEquipmentGroupEquipments(group.id).map(
                    (equipment, index) => (
                      <div key={index} className="ml-4">
                        <MenuItem
                          value={equipment.id}
                          disabled={!equipment.operational}
                          onClick={(event) =>
                            handleMenuItemClick(event, equipment.id)
                          }
                        >
                          <Stack
                            direction="row"
                            justifyContent="space-between"
                            alignItems="center"
                            width="100%"
                          >
                            {equipment.name}
                            <Box sx={{ flexGrow: 1 }} />
                            <Checkbox
                              checked={
                                field.value
                                  ? field.value.includes(equipment.id)
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
        </div>
      ))}
    </TextField>
  );
}
