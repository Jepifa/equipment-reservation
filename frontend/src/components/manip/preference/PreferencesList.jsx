/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect } from "react";

import { TableCell, TableRow } from "@mui/material";

import {
  useDeletePreferenceMutation,
  useGetPreferencesByUserQuery,
} from "./preferencesSlice.service";

import EditDeleteButtons from "../../util/table_components/EditDeleteButtons";
import MyTable from "../../util/table_components/MyTable";
import PreferencesForm from "./PreferencesForm";

/**
 * Component for rendering a list of preferences with edit and delete functionality.
 * @param {Object} props - The props object.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - The rendered component.
 */
const PreferencesList = ({ setErrorMessage }) => {
  const {
    data: preferences = [],
    isFetching,
    isLoading,
    isError: isGetPreferencesError,
  } = useGetPreferencesByUserQuery();

  const [deletePreference] = useDeletePreferenceMutation(); // Destructuring delete mutation hook

  const titles = [
    "Name",
    "Manip name",
    "Location",
    "Site",
    "Equipment",
    "Team",
  ];

  useEffect(() => {
    if (isGetPreferencesError) {
      setErrorMessage(
        "An error occurred while loading preferences. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  /**
   * Handles confirmation of preference deletion.
   * @param {number} preferenceIdToDelete - The ID of the preference to delete.
   * @returns {Promise<void>} - A promise representing the async deletion process.
   */
  const handleConfirmDelete = async (preferenceIdToDelete) => {
    try {
      await deletePreference(preferenceIdToDelete).unwrap(); // Deleting the preference with the specified ID
    } catch (error) {
      setErrorMessage(
        "An error occurred while deleting preference. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  return (
    <MyTable
      titles={titles} // Table titles
      rows={preferences.map((preference) => (
        <TableRow key={preference.id}>
          <TableCell>
            {preference.name} {/* Displaying preference name */}
          </TableCell>
          <TableCell>
            {preference.manipName} {/* Displaying manip name */}
          </TableCell>
          <TableCell>
            {preference.locationName} {/* Displaying location name */}
          </TableCell>
          <TableCell>
            {preference.siteName} {/* Displaying site name */}
          </TableCell>
          <TableCell>
            {preference.equipments.map((equipment) => (
              <p key={equipment.id}>{equipment.name}</p>
            ))}
            {/* Displaying equipments name */}
          </TableCell>
          <TableCell>
            {preference.team.map((user) => (
              <p key={user.id}>{user.name}</p>
            ))}
            {/* Displaying team members name */}
          </TableCell>
          <TableCell width="10%">
            {/* Edit and delete buttons for the preference */}
            <EditDeleteButtons
              rowId={preference.id}
              deleteRow={handleConfirmDelete}
              formComponent={
                <PreferencesForm
                  preference={preference}
                  setErrorMessage={setErrorMessage}
                />
              }
            />
          </TableCell>
        </TableRow>
      ))}
      isLoadingOrFetching={isLoading || isFetching} // Loading state indicator
      rowsNum={preferences.length === 0 ? 10 : preferences.length} // Number of rows in the table
    />
  );
};

export default PreferencesList;
