/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Box, Divider, Drawer, Toolbar } from "@mui/material";

import TabContent from "./TabContent";
import TabsBar from "./TabsBar";

/**
 * TabsContainer component renders a drawer containing tabs and their content.
 * @param {Object} props - The props object containing the following properties:
 * @param {Array<Object>} props.tabs - Array of tab objects.
 * @param {Function} props.setTabs - Function to update the tabs array.
 * @param {number} props.value - Index of the active tab.
 * @param {Function} props.setValue - Function to set the index of the active tab.
 * @param {Function} props.setErrorMessage - Function to set error messages.
 * @returns {JSX.Element} The TabsContainer component JSX.
 */
const TabsContainer = ({ tabs, setTabs, value, setValue, setErrorMessage }) => {
  return (
    <>
      {/* Render the drawer only if there are tabs */}
      {tabs.length !== 0 && (
        <Drawer
          variant="permanent"
          sx={{
            width: "33.333333%",
            flexShrink: 0,
            [`& .MuiDrawer-paper`]: {
              width: "33.333333%",
              boxSizing: "border-box",
              backgroundColor: "inherit",
            },
          }}
          anchor="right"
        >
          <Toolbar />
          <Box sx={{ overflow: "auto", mt: 2 }}>
            {/* Render TabsBar component to display tabs */}
            <TabsBar
              value={value}
              tabs={tabs}
              setValue={setValue}
              setTabs={setTabs}
            />
            <Divider sx={{ mb: 2 }} />

            <div className=" px-2">
              {/* Render TabContent component to display tab content */}
              <TabContent
                value={value}
                setValue={setValue}
                tabs={tabs}
                setTabs={setTabs}
                setErrorMessage={setErrorMessage}
              />
            </div>
          </Box>
        </Drawer>
      )}
    </>
  );
};

export default TabsContainer;
