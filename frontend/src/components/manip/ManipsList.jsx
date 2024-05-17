/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { TableCell, TableRow } from "@mui/material";

import { useDeleteManipMutation } from "./manipsSlice.service";

import EditDeleteButtons from "../util/table_components/EditDeleteButtons";
import ManipsForm from "./manips-form/ManipsForm";
import MyTable from "../util/table_components/MyTable";

/**
 * ManipsList component to display a list of manipulations.
 * @param {Object} props - The props object.
 * @param {boolean} props.isAdmin - Indicates whether the user is an admin.
 * @param {Array<Object>} props.manips - The list of manipulations.
 * @param {boolean} props.isLoading - Indicates if the data is loading.
 * @param {boolean} props.isFetching - Indicates if the data is being fetched.
 * @param {function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - The ManipsList component.
 */
const ManipsList = ({
  isAdmin,
  manips,
  isLoading,
  isFetching,
  setErrorMessage,
}) => {
  const [deleteManip] = useDeleteManipMutation(); // Destructuring delete mutation hook

  const defaultTitles = [
    "Name",
    "Location",
    "Site",
    "Equipment",
    "Team",
    "BeginDate",
    "EndDate",
  ];
  const titles = isAdmin ? ["Pro", ...defaultTitles] : defaultTitles;

  /**
   * Handles confirmation of manip deletion.
   * @param {number} manipIdToDelete - The ID of the manip to delete.
   * @returns {Promise<void>} - A promise representing the async deletion process.
   */
  const handleConfirmDelete = async (manipIdToDelete) => {
    try {
      await deleteManip(manipIdToDelete).unwrap(); // Deleting the manip with the specified ID
    } catch (error) {
      setErrorMessage(
        "An error occurred while deleting manip. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  return (
    <MyTable
      titles={titles} // Table titles
      rows={manips.map((manip) => (
        <TableRow key={manip.id}>
          {isAdmin && (
            <TableCell>
              {manip.userName} {/* Displaying manip pro */}
            </TableCell>
          )}
          <TableCell>
            {manip.name} {/* Displaying manip name */}
          </TableCell>
          <TableCell>
            {manip.locationName} {/* Displaying location name */}
          </TableCell>
          <TableCell>
            {manip.siteName} {/* Displaying site name */}
          </TableCell>
          <TableCell>
            {manip.equipments.map((equipment) => (
              <p key={equipment.id}>{equipment.name}</p>
            ))}
            {/* Displaying equipments name */}
          </TableCell>
          <TableCell>
            {manip.team.map((user) => (
              <p key={user.id}>{user.name}</p>
            ))}
            {/* Displaying team members name */}
          </TableCell>
          <TableCell>
            {manip.beginDate} {/* Displaying begin date */}
          </TableCell>
          <TableCell>
            {manip.endDate} {/* Displaying end date */}
          </TableCell>
          <TableCell width="10%">
            {/* Edit and delete buttons for the manip */}
            <EditDeleteButtons
              rowId={manip.id}
              deleteRow={handleConfirmDelete}
              formComponent={
                <ManipsForm
                  manip={manip}
                  isAdmin={isAdmin}
                  setErrorMessage={setErrorMessage}
                />
              }
            />
          </TableCell>
        </TableRow>
      ))}
      isLoadingOrFetching={isLoading || isFetching} // Loading state indicator
      rowsNum={manips.length === 0 ? 10 : manips.length} // Number of rows in the table
    />
  );
};

export default ManipsList;
