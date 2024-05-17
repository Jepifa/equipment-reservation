/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { TextField } from "@mui/material";

/**
 * Component for rendering a customized text field.
 * @param {Object} props - The component props.
 * @param {Object} props.field - Field props provided by Formik.
 * @param {string} props.label - The label for the text field.
 * @param {Object} props.props - Additional props to be passed to the TextField component.
 * @returns {JSX.Element} - The rendered component.
 */
const MyPasswordTextField = ({
  field,
  form: { touched, errors },
  ...props
}) => {
  return (
    <TextField
      {...field}
      {...props}
      type="password"
      variant="outlined"
      autoComplete="off"
      error={touched[field.name] && Boolean(errors[field.name])}
      helperText={touched[field.name] && errors[field.name]}
    />
  );
};

export default MyPasswordTextField;
