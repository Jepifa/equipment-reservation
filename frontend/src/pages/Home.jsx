/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect, useState } from "react";

import { Alert, Fab, Snackbar, Typography } from "@mui/material";
import AddIcon from "@mui/icons-material/Add";

import { useGetManipsQuery } from "../components/manip/manipsSlice.service";

import Calendar from "../components/home/calendar/Calendar";
import TabsContainer from "../components/home/TabsContainer";
import useAuthContext from "../context/AuthContext";

/**
 * Component for the home page.
 * @returns {JSX.Element} The JSX representing the home page component.
 */
const Home = () => {
  const { data: manips = [], isError: isGetManipsError } = useGetManipsQuery();

  const { validated } = useAuthContext();

  const [errorMessage, setErrorMessage] = useState(null);
  const [tabs, setTabs] = useState([]);
  const [value, setValue] = useState();

  useEffect(() => {
    if (isGetManipsError) {
      setErrorMessage(
        "An error occurred while loading manips. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  }, [isGetManipsError]);

  /**
   * Handle click on a manipulation.
   * @param {Object} manip - The manipulation object.
   */
  const handleManipClick = (manip) => {
    const tabIndex = tabs.findIndex((tab) => tab.manip?.id === manip?.id);

    if (tabIndex === -1) {
      const newTab =
        manip === null
          ? { name: "Create manip", manip: null }
          : { name: manip.name, manip: manip };

      const index = tabs.length;
      setTabs([...tabs, newTab]);
      setValue(index);
    } else {
      setValue(tabIndex);
    }
  };

  return (
    <div className="mx-auto">
      {/* Snackbar for displaying error messages */}
      <Snackbar
        open={!!errorMessage}
        autoHideDuration={6000}
        onClose={() => setErrorMessage(null)}
      >
        <Alert severity="error">{errorMessage}</Alert>
      </Snackbar>
      <div>
        {/* Display content based on user validation */}
        {!validated() ? (
          <div className="flex min-h-screen">
            <Typography sx={{ mt: 4, ml: 10 }}>
              Pending register validation by an admin.
            </Typography>
          </div>
        ) : (
          <div className="flex min-h-screen">
            {/* Calendar component */}
            <div className={tabs.length !== 0 ? "w-2/3 p-4" : "w-full p-4"}>
              <Calendar
                handleManipClick={handleManipClick}
                manips={manips}
                setErrorMessage={setErrorMessage}
              />
            </div>

            {/* TabsContainer component */}
            <TabsContainer
              value={value}
              tabs={tabs}
              setTabs={setTabs}
              setValue={setValue}
              setErrorMessage={setErrorMessage}
            />

            {/* Floating action button for creating a new manipulation */}
            <div
              className={`fixed bottom-16 ${
                tabs.length !== 0 ? "right-1/3" : "right-16"
              } z-10`}
            >
              <Fab
                color="primary"
                aria-label="add"
                title="Create manip"
                className={tabs.length !== 0 ? "right-16" : ""}
                onClick={() => handleManipClick(null)}
              >
                <AddIcon />
              </Fab>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default Home;
