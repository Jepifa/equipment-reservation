/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import "dayjs/locale/en-gb";

import { AdapterDayjs } from "@mui/x-date-pickers/AdapterDayjs";
import { DatePicker, LocalizationProvider } from "@mui/x-date-pickers";

/**
 * Component for rendering a customized date time picker field.
 * @param {Object} props - The component props.
 * @param {Object} props.field - Field props provided by Formik.
 * @param {string} props.label - The label for the text field.
 * @param {Object} props.props - Additional props to be passed to the TextField component.
 * @returns {JSX.Element} - The rendered component.
 */
const MyDatePickerField = ({
  field,
  form: { touched, errors, setFieldValue },
  ...props
}) => {
  return (
    <>
      <LocalizationProvider dateAdapter={AdapterDayjs} adapterLocale="en-gb">
        <DatePicker
          {...props}
          {...field}
          onChange={(value) => {
            setFieldValue(field.name, value);
          }}
          slotProps={{
            textField: {
              variant: "outlined",
              error: touched[field.name] && Boolean(errors[field.name]),
              helperText: touched[field.name] && errors[field.name],
            },
          }}
        />
      </LocalizationProvider>
    </>
  );
};

export default MyDatePickerField;
