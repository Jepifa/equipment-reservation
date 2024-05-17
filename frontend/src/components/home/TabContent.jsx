/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect, useState } from "react";

import { Stack, Typography } from "@mui/material";

import {
  useDeleteManipMutation,
  useGetManipQuery,
} from "../manip/manipsSlice.service";

import DeleteButton from "../util/table_components/DeleteButton";
import EditButton from "../util/table_components/EditButton";
import ManipDetails from "../manip/ManipDetails";
import ManipsForm from "../manip/manips-form/ManipsForm";
import useAuthContext from "../../context/AuthContext";

/**
 * TabContent component manages the content of each tab in a tabbed interface.
 * @param {Object} props - The props object containing the following properties:
 * @param {Array<Object>} props.tabs - Array of tab objects.
 * @param {number} props.value - Index of the active tab.
 * @param {Function} props.setTabs - Function to update the tabs array.
 * @param {Function} props.setValue - Function to set the index of the active tab.
 * @param {Function} props.setErrorMessage - Function to set error messages.
 * @returns {JSX.Element} The TabContent component JSX.
 */
const TabContent = ({ tabs, value, setTabs, setValue, setErrorMessage }) => {
  const { user } = useAuthContext();

  // Query manipulation data for the active tab
  const {
    data: manip = null,
    refetch,
    isError: isGetManipError,
  } = useGetManipQuery(tabs[value].manip?.id);

  // Mutation hook to delete manipulation
  const [deleteManip] = useDeleteManipMutation();

  // State to track if manipulation data has been updated
  const [isUpdated, setIsUpdated] = useState(false);
  // State to track tabs that need to be updated
  const [tabsToUpdate, setTabsToUpdate] = useState([]);

  // Effect to handle errors when getting manipulation data
  useEffect(() => {
    if (isGetManipError && tabs?.manip) {
      setErrorMessage(
        "An error occurred while loading manip. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  // Effect to update tabs when manipulation data changes
  useEffect(() => {
    if (isUpdated) {
      tabs[value].manip = manip;
      tabs[value].name = manip ? manip.name : "Create manip";
      setIsUpdated(false);
    }

    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [manip]);

  // Function to handle deletion of a manipulation
  const handleConfirmDelete = async (manipIdToDelete) => {
    try {
      await deleteManip(manipIdToDelete); // Deleting the manip with the specified ID
      setTabs((prevTabs) => prevTabs.filter((tab, index) => index !== value));
      setValue(Math.max(value - 1, 0));
    } catch (error) {
      setErrorMessage(
        "An error occurred while deleting manip. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  // Function to add a tab to the list of tabs to update
  const handleUpdate = () => {
    const newTabsToUpdate = [...tabsToUpdate, tabs[value]];
    setTabsToUpdate(newTabsToUpdate);
  };

  // Function to handle updated tab
  const handleTabUpdated = async () => {
    await refetch();
    setTabsToUpdate((prevTabsToUpdate) =>
      prevTabsToUpdate.filter((tab) => tab !== tabs[value])
    );
    setIsUpdated(true);
  };

  return (
    <>
      {/* Render each tab */}
      {tabs.map((tab, index) => (
        <div key={index} role="tabpanel" hidden={value != index}>
          {
            // Conditional rendering based on manipulation data
            <>
              {tab?.manip && !tabsToUpdate.includes(tab) ? (
                // Render manipulation details if manipulation exists and not marked for update
                <>
                  <Stack
                    direction="row"
                    alignItems="center"
                    justifyContent="space-between"
                    marginBottom={2}
                  >
                    <Typography variant="h4">{tab.name}</Typography>
                    {tab.manip.userId === user.id && (
                      // Render edit and delete buttons if user is the owner of the manipulation
                      <Stack direction="row" spacing={2}>
                        <EditButton
                          rowId={tab?.manip.id}
                          updateRow={handleUpdate}
                        />
                        <DeleteButton
                          rowId={tab?.manip.id}
                          deleteRow={handleConfirmDelete}
                        />
                      </Stack>
                    )}
                  </Stack>
                  <ManipDetails manip={tab?.manip} />
                </>
              ) : (
                // Render manipulation form if manipulation doesn't exist or marked for update
                <>
                  {tab?.manip ? (
                    <Typography variant="h4" sx={{ mb: 4 }}>
                      Update manip
                    </Typography>
                  ) : (
                    <Typography variant="h4" sx={{ mb: 4 }}>
                      Create manip
                    </Typography>
                  )}
                  <ManipsForm
                    manip={tab?.manip}
                    handleTabUpdated={handleTabUpdated}
                    setErrorMessage={setErrorMessage}
                  />
                </>
              )}
            </>
          }
        </div>
      ))}
    </>
  );
};

export default TabContent;
