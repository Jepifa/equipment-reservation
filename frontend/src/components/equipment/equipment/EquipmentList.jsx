/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect, useState } from "react";

import {
  Checkbox,
  CircularProgress,
  IconButton,
  Stack,
  TableCell,
  TableRow,
} from "@mui/material";

import {
  useDeleteEquipmentMutation,
  useGetEquipmentsQuery,
  useUpdateEquipmentMutation,
} from "./equipmentSlice.service";

import EquipmentForm from "./EquipmentForm";
import EditDeleteButtons from "../../util/table_components/EditDeleteButtons";
import MyTable from "../../util/table_components/MyTable";

/**
 * Component for rendering a list of equipment with edit and delete functionality.
 * @param {Object} props - The component props.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - The rendered component.
 */
const EquipmentList = ({ setErrorMessage }) => {
  const {
    data: equipments = [],
    isLoading,
    isFetching,
    isError: isGetEquipmentsError,
  } = useGetEquipmentsQuery(); // Fetching equipment data using useGetEquipmentsQuery hook

  const [deleteEquipment] = useDeleteEquipmentMutation(); // Destructuring delete mutation hook
  const [updateEquipment, { isLoading: isUpdating }] =
    useUpdateEquipmentMutation(); // Destructuring update mutation hook

  const [updatingEquipmentId, setUpdatingEquipmentId] = useState("");

  useEffect(() => {
    if (isGetEquipmentsError) {
      setErrorMessage(
        "An error occurred while loading equipment. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  /**
   * Handles confirmation of equipment deletion.
   * @param {number} equipmentIdToDelete - The ID of the equipment to delete.
   * @returns {Promise<void>} - A promise representing the async deletion process.
   */
  const handleConfirmDelete = async (equipmentIdToDelete) => {
    try {
      await deleteEquipment(equipmentIdToDelete).unwrap(); // Deleting the equipment with the specified ID
    } catch (error) {
      setErrorMessage(
        "An error occurred while deleting equipment. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  /**
   * Handles equipment update.
   * @param {object} equipmentToUpdate - The equipment object to update.
   * @returns {Promise<void>} - A promise representing the async deletion process.
   */
  const handleUpdate = async (equipmentToUpdate) => {
    try {
      setUpdatingEquipmentId(equipmentToUpdate.id);
      const updatedEquipment = {
        ...equipmentToUpdate,
        operational: !equipmentToUpdate.operational, // Toggle operational status
      };
      await updateEquipment(updatedEquipment).unwrap(); // Updating the equipment with the specified ID
      setUpdatingEquipmentId("");
    } catch (error) {
      setErrorMessage(
        "An error occurred while updating equipment. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  return (
    <MyTable
      titles={["Name", "Equipment group", "Category", "Operational"]} // Table titles
      rows={equipments.map((equipment) => (
        <TableRow key={equipment.id}>
          <TableCell>{equipment.name}</TableCell>
          {/* Displaying equipment name */}
          <TableCell>{equipment.equipmentGroupName}</TableCell>
          {/* Displaying equipment group name */}
          <TableCell>{equipment.categoryName}</TableCell>
          {/* Displaying category name */}
          <TableCell width="10%">
            <Stack alignItems="center" sx={{ justifyContent: "center" }}>
              {isUpdating && equipment.id === updatingEquipmentId ? (
                <IconButton>
                  <CircularProgress size={24} />
                </IconButton>
              ) : (
                <Checkbox
                  checked={equipment.operational}
                  onChange={() => handleUpdate(equipment)}
                />
              )}
            </Stack>

            {/* Displaying operational */}
          </TableCell>
          <TableCell width="10%">
            {/* Edit and delete buttons for the equipment */}
            <EditDeleteButtons
              rowId={equipment.id}
              deleteRow={handleConfirmDelete}
              formComponent={
                <EquipmentForm
                  equipment={equipment}
                  setErrorMessage={setErrorMessage}
                />
              }
            />
          </TableCell>
        </TableRow>
      ))}
      isLoadingOrFetching={isLoading || isFetching} // Loading state indicator
      rowsNum={equipments.length === 0 ? 10 : equipments.length} // Number of rows in the table
    />
  );
};

export default EquipmentList;
