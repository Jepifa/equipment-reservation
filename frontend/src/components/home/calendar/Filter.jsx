/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import {
  Box,
  Checkbox,
  Chip,
  ListItemText,
  MenuItem,
  TextField,
} from "@mui/material";

/**
 * Filter component for selecting items from a list.
 * @param {Object} props - Component props.
 * @param {Array} props.items - Array of items to display in the filter.
 * @param {Array} props.selectedItems - Array of selected items.
 * @param {Function} props.handleFilterChange - Function to handle filter changes.
 * @param {Object} props - Additional props to pass to the TextField component.
 * @returns {JSX.Element} - Filter component.
 */
const Filter = ({ items, selectedItems, handleFilterChange, ...props }) => {
  return (
    <TextField
      {...props}
      select
      onChange={(event) => handleFilterChange(event)}
      SelectProps={{
        multiple: true,
        MenuProps: {
          style: {
            maxHeight: 400,
          },
        },
        value: selectedItems,
        renderValue: () => (
          <Box
            sx={{
              display: "flex",
              overflowX: "scroll",
              flexWrap: "nowrap",
              "&::-webkit-scrollbar": {
                display: "none",
              },
              scrollbarWidth: "none",
              gap: 0.5,
            }}
          >
            {selectedItems.map((item) => (
              <Chip key={item.id} label={item.name} />
            ))}
          </Box>
        ),
      }}
    >
      {items.map((item) => (
        <MenuItem key={item.id} value={item}>
          <Checkbox checked={selectedItems.indexOf(item) > -1} />
          <ListItemText primary={item.name} />
        </MenuItem>
      ))}
    </TextField>
  );
};

export default Filter;
