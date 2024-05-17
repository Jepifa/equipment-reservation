/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect } from "react";

import { TableCell, TableRow } from "@mui/material";

import { useDeleteSiteMutation, useGetSitesQuery } from "./sitesSlice.service";

import EditDeleteButtons from "../../util/table_components/EditDeleteButtons";
import MyTable from "../../util/table_components/MyTable";
import SitesForm from "./SitesForm";

/**
 * Component for rendering a list of sites with edit and delete functionality.
 * @param {Object} props - The props object.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - The rendered component.
 */
const SitesList = ({ setErrorMessage }) => {
  const {
    data: sites = [],
    isLoading,
    isFetching,
    isError: isGetSitesError,
  } = useGetSitesQuery(); // Fetching sites data using useGetSitesQuery hook

  const [deleteSite] = useDeleteSiteMutation(); // Destructuring delete mutation hook

  useEffect(() => {
    if (isGetSitesError) {
      setErrorMessage(
        "An error occurred while loading sites. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  /**
   * Handles confirmation of site deletion.
   * @param {number} siteIdToDelete - The ID of the site to delete.
   * @returns {Promise<void>} - A promise representing the async deletion process.
   */
  const handleConfirmDelete = async (siteIdToDelete) => {
    try {
      await deleteSite(siteIdToDelete).unwrap(); // Deleting the site with the specified ID
    } catch (error) {
      setErrorMessage(
        "An error occurred while deleting site. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  return (
    <MyTable
      titles={["Name"]} // Table titles
      rows={sites.map((site) => (
        <TableRow key={site.id}>
          <TableCell>
            {site.name} {/* Displaying site name */}
          </TableCell>
          <TableCell width="10%">
            {/* Edit and delete buttons for the site */}
            <EditDeleteButtons
              rowId={site.id}
              deleteRow={handleConfirmDelete}
              formComponent={
                <SitesForm site={site} setErrorMessage={setErrorMessage} />
              }
            />
          </TableCell>
        </TableRow>
      ))}
      isLoadingOrFetching={isLoading || isFetching} // Loading state indicator
      rowsNum={sites.length === 0 ? 10 : sites.length} // Number of rows in the table
    />
  );
};

export default SitesList;
