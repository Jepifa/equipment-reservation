/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect, useState } from "react";
import dayjs from "dayjs";
import "dayjs/locale/en-gb";

import { AdapterDayjs } from "@mui/x-date-pickers/AdapterDayjs";
import { DateTimePicker, LocalizationProvider } from "@mui/x-date-pickers";

/**
 * Component for rendering a customized date time picker field.
 * @param {Object} props - The component props.
 * @param {Object} props.field - Field props provided by Formik.
 * @param {string} props.label - The label for the text field.
 * @param {Object} props.props - Additional props to be passed to the TextField component.
 * @returns {JSX.Element} - The rendered component.
 */
const MyDateTimePickerField = ({
  field,
  form: { touched, errors, setFieldValue, submitCount },
  ...props
}) => {
  const [selectedDate, setSelectedDate] = useState(field.value);

  useEffect(() => {
    setFieldValue(field.name, selectedDate?.format("YYYY-MM-DDTHH:mm:ss"));
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [selectedDate]);

  return (
    <>
      <LocalizationProvider dateAdapter={AdapterDayjs} adapterLocale="en-gb">
        <DateTimePicker
          {...props}
          {...field}
          value={selectedDate}
          onChange={(value) => {
            if (value.$H === 0) {
              setSelectedDate(
                value?.set("hour", 7).set("minute", 0).set("second", 0)
              );
            } else {
              setSelectedDate(value);
            }
          }}
          defaultValue={dayjs()
            .set("hour", 7)
            .set("minute", 0)
            .set("second", 0)}
          minTime={dayjs().set("hour", 7).set("minute", 0).set("second", 0)}
          maxTime={dayjs().set("hour", 19).set("minute", 0)}
          timeSteps={{ minutes: 30 }}
          ampm={false}
          slotProps={{
            textField: {
              variant: "outlined",
              error:
                (touched[field.name] || submitCount > 0) &&
                Boolean(errors[field.name]),
              helperText:
                (touched[field.name] || submitCount > 0) && errors[field.name],
            },
          }}
        />
      </LocalizationProvider>
    </>
  );
};

export default MyDateTimePickerField;
