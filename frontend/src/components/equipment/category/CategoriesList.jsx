/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect } from "react";

import { TableCell, TableRow } from "@mui/material";

import {
  useDeleteCategoryMutation,
  useGetCategoriesQuery,
} from "./categoriesSlice.service";

import CategoriesForm from "./CategoriesForm";
import EditDeleteButtons from "../../util/table_components/EditDeleteButtons";
import MyTable from "../../util/table_components/MyTable";

/**
 * Component for rendering a list of categories with edit and delete functionality.
 * @param {Object} props - The component props.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - The rendered component.
 */
const CategoriesList = ({ setErrorMessage }) => {
  const {
    data: categories = [],
    isLoading,
    isFetching,
    isError: isGetCategoriesError,
  } = useGetCategoriesQuery(); // Fetching categories data using useGetCategoriesQuery hook

  const [deleteCategory] = useDeleteCategoryMutation(); // Destructuring delete mutation hook

  useEffect(() => {
    if (isGetCategoriesError) {
      setErrorMessage(
        "An error occurred while loading categories. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  /**
   * Handles confirmation of category deletion.
   * @param {number} categoryIdToDelete - The ID of the category to delete.
   * @returns {Promise<void>} - A promise representing the async deletion process.
   */
  const handleConfirmDelete = async (categoryIdToDelete) => {
    try {
      await deleteCategory(categoryIdToDelete).unwrap(); // Deleting the category with the specified ID
    } catch (error) {
      setErrorMessage(
        "An error occurred while deleting category. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  return (
    <MyTable
      titles={["Name"]} // Table titles
      rows={categories.map((category) => (
        <TableRow key={category.id}>
          <TableCell>
            {category.name} {/* Displaying category name */}
          </TableCell>
          <TableCell width="10%">
            {/* Edit and delete buttons for the category */}
            <EditDeleteButtons
              rowId={category.id}
              deleteRow={handleConfirmDelete}
              formComponent={
                <CategoriesForm
                  category={category}
                  setErrorMessage={setErrorMessage}
                />
              }
            />
          </TableCell>
        </TableRow>
      ))}
      isLoadingOrFetching={isLoading || isFetching} // Loading state indicator
      rowsNum={categories.length === 0 ? 10 : categories.length} // Number of rows in the table
    />
  );
};

export default CategoriesList;
