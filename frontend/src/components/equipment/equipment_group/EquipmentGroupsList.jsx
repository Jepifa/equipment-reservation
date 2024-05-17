/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect } from "react";

import { TableCell, TableRow } from "@mui/material";

import {
  useDeleteEquipmentGroupMutation,
  useGetEquipmentGroupsQuery,
} from "./equipmentGroupsSlice.service";

import EditDeleteButtons from "../../util/table_components/EditDeleteButtons";
import EquipmentGroupsForm from "./EquipmentGroupsForm";
import MyTable from "../../util/table_components/MyTable";

/**
 * Component for rendering a list of equipment groups with edit and delete functionality.
 * @param {Object} props - The component props.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - The rendered component.
 */
const EquipmentGroupsList = ({ setErrorMessage }) => {
  const {
    data: equipmentGroups = [],
    isLoading,
    isFetching,
    isError: isGetEquipmentGroupsError,
  } = useGetEquipmentGroupsQuery(); // Fetching equipment groups data using useGetEquipmentGroupsQuery hook

  const [deleteEquipmentGroup] = useDeleteEquipmentGroupMutation(); // Destructuring delete mutation hook

  useEffect(() => {
    if (isGetEquipmentGroupsError) {
      setErrorMessage(
        "An error occurred while loading equipment groups. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  /**
   * Handles confirmation of equipment group deletion.
   * @param {number} equipmentGroupIdToDelete - The ID of the equipment group to delete.
   * @returns {Promise<void>} - A promise representing the async deletion process.
   */
  const handleConfirmDelete = async (equipmentGroupIdToDelete) => {
    try {
      await deleteEquipmentGroup(equipmentGroupIdToDelete).unwrap(); // Deleting the equipment group with the specified ID
    } catch (error) {
      setErrorMessage(
        "An error occurred while deleting equipment group. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  return (
    <MyTable
      titles={["Name", "Category"]} // Table titles
      rows={equipmentGroups.map((equipmentGroup) => (
        <TableRow key={equipmentGroup.id}>
          <TableCell>
            {equipmentGroup.name} {/* Displaying equipment group name */}
          </TableCell>
          <TableCell>
            {equipmentGroup.categoryName} {/* Displaying category name */}
          </TableCell>
          <TableCell width="10%">
            {/* Edit and delete buttons for the equipment group */}
            <EditDeleteButtons
              rowId={equipmentGroup.id}
              deleteRow={handleConfirmDelete}
              formComponent={
                <EquipmentGroupsForm
                  equipmentGroup={equipmentGroup}
                  setErrorMessage={setErrorMessage}
                />
              }
            />
          </TableCell>
        </TableRow>
      ))}
      isLoadingOrFetching={isLoading || isFetching} // Loading state indicator
      rowsNum={equipmentGroups.length === 0 ? 10 : equipmentGroups.length} // Number of rows in the table
    />
  );
};

export default EquipmentGroupsList;
