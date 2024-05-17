/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect } from "react";

import { TableCell, TableRow } from "@mui/material";

import {
  useDeleteLocationMutation,
  useGetLocationsQuery,
} from "./locationsSlice.service";

import EditDeleteButtons from "../../util/table_components/EditDeleteButtons";
import LocationsForm from "./LocationsForm";
import MyTable from "../../util/table_components/MyTable";

/**
 * Component for rendering a list of locations with edit and delete functionality.
 * @param {Object} props - The props object.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - The rendered component.
 */
const LocationsList = ({ setErrorMessage }) => {
  const {
    data: locations = [],
    isLoading,
    isFetching,
    isError: isGetLocationsError,
  } = useGetLocationsQuery(); // Fetching locations data using useGetLocationsQuery hook

  const [deleteLocation] = useDeleteLocationMutation(); // Destructuring delete mutation hook

  useEffect(() => {
    if (isGetLocationsError) {
      setErrorMessage(
        "An error occurred while loading locations. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  /**
   * Handles confirmation of location deletion.
   * @param {number} locationIdToDelete - The ID of the location to delete.
   * @returns {Promise<void>} - A promise representing the async deletion process.
   */
  const handleConfirmDelete = async (locationIdToDelete) => {
    try {
      await deleteLocation(locationIdToDelete).unwrap(); // Deleting the location with the specified ID
    } catch (error) {
      setErrorMessage(
        "An error occurred while deleting location. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  return (
    <MyTable
      titles={["Name", "Site"]} // Table titles
      rows={locations.map((location) => (
        <TableRow key={location.id}>
          <TableCell>
            {location.name} {/* Displaying location name */}
          </TableCell>
          <TableCell>
            {location.siteName} {/* Displaying site name */}
          </TableCell>
          <TableCell width="10%">
            {/* Edit and delete buttons for the location */}
            <EditDeleteButtons
              rowId={location.id}
              deleteRow={handleConfirmDelete}
              formComponent={
                <LocationsForm
                  location={location}
                  setErrorMessage={setErrorMessage}
                />
              }
            />
          </TableCell>
        </TableRow>
      ))}
      isLoadingOrFetching={isLoading || isFetching} // Loading state indicator
      rowsNum={locations.length === 0 ? 10 : locations.length} // Number of rows in the table
    />
  );
};

export default LocationsList;
