/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect, useState } from "react";

import {
  Checkbox,
  CircularProgress,
  IconButton,
  MenuItem,
  Stack,
  TableCell,
  TableRow,
  TextField,
  Typography,
} from "@mui/material";

import {
  useDeleteUserMutation,
  useGetUsersQuery,
  useUpdateUserMutation,
} from "./usersSlice.service";

import DeleteButton from "../util/table_components/DeleteButton";
import MyTable from "../util/table_components/MyTable";

/**
 * Component for rendering a list of user with edit and delete functionality.
 * @param {Object} props - The props object.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - The rendered component.
 */
const UsersList = ({ setErrorMessage }) => {
  const {
    data: users = [],
    isLoading,
    isFetching,
    isError: isGetUsersError,
  } = useGetUsersQuery(); // Fetching user data using useGetUsersQuery hook

  const [deleteUser] = useDeleteUserMutation(); // Destructuring delete mutation hook
  const [updateUser, { isLoading: isUpdating }] = useUpdateUserMutation(); // Destructuring update mutation hook

  const [updatingUserId, setUpdatingUserId] = useState("");

  useEffect(() => {
    if (isGetUsersError) {
      setErrorMessage(
        "An error occurred while loading users. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  /**
   * Handles confirmation of user deletion.
   * @param {number} userIdToDelete - The ID of the user to delete.
   * @returns {Promise<void>} - A promise representing the async deletion process.
   */
  const handleConfirmDelete = async (userIdToDelete) => {
    try {
      await deleteUser(userIdToDelete).unwrap(); // Deleting the user with the specified ID
    } catch (error) {
      setErrorMessage(
        "An error occurred while deleting user. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  /**
   * Handles user update.
   * @param {object} userToUpdate - The user object to update.
   * @returns {Promise<void>} - A promise representing the async deletion process.
   */
  const handleUpdate = async (userToUpdate) => {
    try {
      setUpdatingUserId(userToUpdate.id);
      const updatedUser = {
        ...userToUpdate,
        validated: !userToUpdate.validated, // Toggle validated status
      };
      await updateUser(updatedUser).unwrap(); // Updating the user with the specified ID
      setUpdatingUserId("");
    } catch (error) {
      setErrorMessage(
        "An error occurred while validating user. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  const handleRoleChange = async (event, user) => {
    try {
      setUpdatingUserId(user.id);
      const newRole = event.target.value;
      const updatedUser = {
        ...user,
        role: newRole,
      };
      await updateUser(updatedUser).unwrap();
      setUpdatingUserId("");
    } catch (error) {
      setErrorMessage(
        "An error occurred while changing role. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  return (
    <>
      {/* Stack component for layout */}
      <Stack
        direction="row"
        justifyContent="space-between"
        alignItems="center"
        mt={2} // Margin top
        mb={2} // Margin bottom
      >
        {/* Heading */}
        <Typography variant="h4">Users</Typography>
      </Stack>
      <MyTable
        titles={["Name", "Email", "Role", "Validated"]} // Table titles
        rows={users.map((user) => (
          <TableRow key={user.id}>
            <TableCell>{user.name}</TableCell>
            {/* Displaying user name */}
            <TableCell>{user.email}</TableCell>
            {/* Displaying user email */}
            <TableCell>
              {isUpdating && user.id === updatingUserId ? (
                <Stack alignItems="center" sx={{ justifyContent: "center" }}>
                  <IconButton>
                    <CircularProgress size={24} />
                  </IconButton>
                </Stack>
              ) : (
                <TextField
                  value={user.role}
                  select
                  onChange={(event) => handleRoleChange(event, user)}
                  fullWidth
                >
                  <MenuItem value="admin">admin</MenuItem>
                  {/* Options du select */}
                  <MenuItem value="user">user</MenuItem>
                </TextField>
              )}
            </TableCell>
            {/* Displaying user role */}
            <TableCell width="10%">
              <Stack alignItems="center" sx={{ justifyContent: "center" }}>
                {isUpdating && user.id === updatingUserId ? (
                  <IconButton>
                    <CircularProgress size={24} />
                  </IconButton>
                ) : (
                  <Checkbox
                    checked={user.validated}
                    onChange={() => handleUpdate(user)}
                  />
                )}
              </Stack>

              {/* Displaying operational */}
            </TableCell>
            <TableCell width="10%">
              {/* Edit and delete buttons for the user */}
              <DeleteButton rowId={user.id} deleteRow={handleConfirmDelete} />
            </TableCell>
          </TableRow>
        ))}
        isLoadingOrFetching={isLoading || isFetching} // Loading state indicator
        rowsNum={users.length === 0 ? 10 : users.length} // Number of rows in the table
      />
    </>
  );
};

export default UsersList;
