/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Box, Chip, MenuItem, OutlinedInput, TextField } from "@mui/material";

/**
 * Component for rendering a customized text field.
 * @param {Object} props - The component props.
 * @param {Object} props.field - Field props provided by Formik.
 * @param {string} props.label - The label for the text field.
 * @param {Object} props.props - Additional props to be passed to the TextField component.
 * @returns {JSX.Element} - The rendered component.
 */
const MyMultipleSelectField = ({
  field,
  form: { touched, errors, setFieldValue },
  items,
  isEquipment,
  ...props
}) => {
  const selectedNames = field.value.map((selectedId) => {
    const selectedItem = items.find((item) => item.id === selectedId);
    return selectedItem ? selectedItem.name : "";
  });

  if (items.length === 0) {
    return (
      <TextField label={props.label + " - Loading..."} disabled></TextField>
    );
  }

  return (
    <TextField
      {...field}
      {...props}
      variant="outlined"
      select
      SelectProps={{
        multiple: true,
        input: <OutlinedInput id="select-multiple-chip" label={props.label} />,
        renderValue: () => (
          <Box sx={{ display: "flex", flexWrap: "wrap", gap: 0.5 }}>
            {selectedNames.map((name, index) => (
              <Chip key={index} label={name} />
            ))}
          </Box>
        ),
        onChange: (event) => {
          setFieldValue(
            field.name,
            Array.isArray(event.target.value) ? event.target.value : []
          );
        },
        value: field.value || [],
        MenuProps: {
          PaperProps: {
            style: {
              maxHeight: 300,
            },
          },
        },
      }}
      autoComplete="off"
      error={touched[field.name] && Boolean(errors[field.name])}
      helperText={touched[field.name] && errors[field.name]}
    >
      {items.map((item) => (
        <MenuItem
          key={item.id}
          value={item.id}
          disabled={isEquipment && !item.operational}
        >
          {item.name}
        </MenuItem>
      ))}
    </TextField>
  );
};

export default MyMultipleSelectField;
