/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { ListItemText, MenuItem, TextField } from "@mui/material";

/**
 * SelectNbDisplays component for choosing the number of displays.
 * @param {Object} props - Component props.
 * @param {Function} props.handleChange - Function to handle change of selected number of displays.
 * @param {number} props.maxIndex - Maximum index of displays.
 * @param {Object} props - Additional props to pass to TextField component.
 * @returns {JSX.Element} - SelectNbDisplays component.
 */
const SelectNbDisplays = ({ handleChange, maxIndex, ...props }) => {
  /**
   * Array of available options for number of displays.
   */
  const items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];

  return (
    <TextField
      {...props}
      select
      onChange={(event) => handleChange(event)}
      SelectProps={{
        MenuProps: {
          style: {
            maxHeight: 400,
          },
        },
        value: maxIndex,
      }}
    >
      {items.map((item) => (
        <MenuItem key={item} value={item}>
          <ListItemText primary={item} />
        </MenuItem>
      ))}
    </TextField>
  );
};

export default SelectNbDisplays;
