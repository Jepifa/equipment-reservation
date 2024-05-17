/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { IconButton, Tab, Tabs } from "@mui/material";
import Close from "@mui/icons-material/Close";

/**
 * TabsBar component renders a tab bar with scrollable tabs and close buttons.
 * @param {Object} props - The props object containing the following properties:
 * @param {number} props.value - Index of the active tab.
 * @param {Array<Object>} props.tabs - Array of tab objects.
 * @param {Function} props.setValue - Function to set the index of the active tab.
 * @param {Function} props.setTabs - Function to update the tabs array.
 * @returns {JSX.Element} The TabsBar component JSX.
 */
const TabsBar = ({ value, tabs, setValue, setTabs }) => {
  /**
   * Handles tab change event.
   * @param {Object} event - The event object.
   * @param {number} newValue - The new value (index) of the active tab.
   */
  const handleChange = (event, newValue) => {
    setValue(newValue);
  };

  /**
   * Handles tab delete event.
   * @param {number} indexToRemove - The index of the tab to be removed.
   */
  const handleTabDelete = (indexToRemove) => {
    setTabs((prevTabs) =>
      prevTabs.filter((tab, index) => index !== indexToRemove)
    );
    if (value >= indexToRemove) {
      setValue(Math.max(value - 1, 0));
    }
  };

  return (
    <Tabs
      value={value}
      onChange={handleChange}
      variant="scrollable"
      scrollButtons="auto"
      aria-label="scrollable auto tabs example"
      sx={{ m: 1 }}
    >
      {/* Render each tab */}
      {tabs.map((tab, index) => (
        <Tab
          key={index}
          component="div"
          label={
            <span>
              {tab.name}
              {/* Close button */}
              <IconButton
                onClick={(e) => {
                  e.stopPropagation();
                  handleTabDelete(index);
                }}
              >
                <Close />
              </IconButton>
            </span>
          }
        />
      ))}
    </Tabs>
  );
};

export default TabsBar;
