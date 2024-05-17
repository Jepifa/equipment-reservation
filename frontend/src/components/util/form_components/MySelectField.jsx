/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { MenuItem, TextField } from "@mui/material";

/**
 * Component for rendering a customized text field.
 * @param {Object} props - The component props.
 * @param {Object} props.field - Field props provided by Formik.
 * @param {string} props.label - The label for the text field.
 * @param {Object} props.props - Additional props to be passed to the TextField component.
 * @returns {JSX.Element} - The rendered component.
 */
const MySelectField = ({
  field,
  form: { touched, errors },
  items,
  ...props
}) => {
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
      autoComplete="off"
      SelectProps={{
        MenuProps: {
          PaperProps: {
            style: {
              maxHeight: 300,
            },
          },
        },
      }}
      error={touched[field.name] && Boolean(errors[field.name])}
      helperText={touched[field.name] && errors[field.name]}
    >
      {items.map((item) => (
        <MenuItem key={item.id} value={item.id}>
          {item.name}
        </MenuItem>
      ))}
    </TextField>
  );
};

export default MySelectField;
