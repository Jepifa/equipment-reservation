/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import {
  Box,
  Checkbox,
  FormControl,
  FormControlLabel,
  FormGroup,
  FormHelperText,
  FormLabel,
  Radio,
  RadioGroup,
  Stack,
} from "@mui/material";
import { Field } from "formik";

import MyDatePickerField from "../../util/form_components/MyDatePickerField";

/**
 * Recurrence component for manipulation form.
 * @param {Object} props - The props object.
 * @param {string} props.selectedRecurrence - The selected recurrence type.
 * @param {Function} props.setSelectedRecurrence - Function to set selected recurrence type.
 * @param {string} props.selectedWeeklyRecurrence - The selected weekly recurrence type.
 * @param {Function} props.setSelectedWeeklyRecurrence - Function to set selected weekly recurrence type.
 * @param {Object} props.multipleDaysRecurrence - The selected multiple days recurrence.
 * @param {Function} props.setMultipleDaysRecurrence - Function to set selected multiple days recurrence.
 * @param {string} props.multipleDaysRecurrenceError - Error message for multiple days recurrence.
 * @param {Function} props.setMultipleDaysRecurrenceError - Function to set error message for multiple days recurrence.
 * @returns {JSX.Element} - The Recurrence component.
 */
const Recurrence = ({
  selectedRecurrence,
  setSelectedRecurrence,
  selectedWeeklyRecurrence,
  setSelectedWeeklyRecurrence,
  multipleDaysRecurrence,
  setMultipleDaysRecurrence,
  multipleDaysRecurrenceError,
  setMultipleDaysRecurrenceError,
}) => {
  // Handle change in recurrence type
  const handleRecurrenceChange = (event) => {
    setSelectedRecurrence(event.target.value);
  };

  // Handle change in weekly recurrence type
  const handleWeeklyRecurrenceChange = (event) => {
    setSelectedWeeklyRecurrence(event.target.value);
  };

  const { monday, tuesday, wednesday, thursday, friday } =
    multipleDaysRecurrence;

  // Handle change in multiple days recurrence
  const handleMultipleDaysChange = (event) => {
    if (multipleDaysRecurrenceError !== "") {
      setMultipleDaysRecurrenceError("");
    }
    setMultipleDaysRecurrence({
      ...multipleDaysRecurrence,
      [event.target.name]: event.target.checked,
    });
  };

  return (
    <>
      <FormControl>
        <Stack
          direction="row"
          justifyContent="space-between"
          alignItems="center"
        >
          <Box sx={{ flexGrow: 1 }} />
          <FormLabel id="row-radio-buttons-recurrence-label">
            Recurrence ?
          </FormLabel>
          <Box sx={{ flexGrow: 1 }} />
          {/* Radio buttons for selecting recurrence type */}
          <RadioGroup
            row
            aria-labelledby="row-radio-buttons-recurrence-label"
            name="radio-buttons-recurrence"
            value={selectedRecurrence}
            onChange={handleRecurrenceChange}
          >
            <FormControlLabel value="daily" control={<Radio />} label="Daily" />
            <FormControlLabel
              value="weekly"
              control={<Radio />}
              label="Weekly"
            />
            <FormControlLabel
              value=""
              control={<Radio />}
              label="No recurrence"
            />
          </RadioGroup>
          <Box sx={{ flexGrow: 1 }} />
        </Stack>
      </FormControl>
      {/* Date picker for end recurrence date if daily recurrence */}
      {selectedRecurrence === "daily" && (
        <FormControl>
          <Field
            name="endRecurrenceDate"
            component={MyDatePickerField}
            label="End recurrence date"
          />
        </FormControl>
      )}
      {/* Additional options for weekly recurrence */}
      {selectedRecurrence === "weekly" && (
        <>
          <FormControl>
            <Stack
              direction="row"
              justifyContent="space-between"
              alignItems="center"
            >
              <Box sx={{ flexGrow: 1 }} />
              <FormLabel id="row-radio-buttons-recurrence-weekly-label">
                Multiple days ?
              </FormLabel>
              <Box sx={{ flexGrow: 1 }} />
              {/* Radio buttons for selecting multiple days recurrence */}
              <RadioGroup
                row
                aria-labelledby="row-radio-buttons-recurrence-weekly-label"
                name="radio-buttons-recurrence-weekly"
                value={selectedWeeklyRecurrence}
                onChange={handleWeeklyRecurrenceChange}
              >
                <FormControlLabel
                  value="multiple"
                  control={<Radio />}
                  label="Yes"
                />
                <FormControlLabel value="" control={<Radio />} label="No" />
              </RadioGroup>
              <Box sx={{ flexGrow: 1 }} />
            </Stack>
          </FormControl>
          {/* Checkboxes for selecting specific days for recurrence */}
          {selectedWeeklyRecurrence === "multiple" && (
            <FormControl
              sx={{ m: 3 }}
              component="fieldset"
              variant="standard"
              error={!!multipleDaysRecurrenceError}
            >
              <FormGroup>
                <Stack
                  direction="row"
                  justifyContent="space-between"
                  alignItems="center"
                >
                  <FormControlLabel
                    control={
                      <Checkbox
                        checked={monday}
                        onChange={handleMultipleDaysChange}
                        name="monday"
                      />
                    }
                    label="Monday"
                  />

                  <Box sx={{ flexGrow: 1 }} />
                  <FormControlLabel
                    control={
                      <Checkbox
                        checked={tuesday}
                        onChange={handleMultipleDaysChange}
                        name="tuesday"
                      />
                    }
                    label="Tuesday"
                  />

                  <Box sx={{ flexGrow: 1 }} />
                  <FormControlLabel
                    control={
                      <Checkbox
                        checked={wednesday}
                        onChange={handleMultipleDaysChange}
                        name="wednesday"
                      />
                    }
                    label="Wednesday"
                  />

                  <Box sx={{ flexGrow: 1 }} />
                  <FormControlLabel
                    control={
                      <Checkbox
                        checked={thursday}
                        onChange={handleMultipleDaysChange}
                        name="thursday"
                      />
                    }
                    label="Thursday"
                  />

                  <Box sx={{ flexGrow: 1 }} />
                  <FormControlLabel
                    control={
                      <Checkbox
                        checked={friday}
                        onChange={handleMultipleDaysChange}
                        name="friday"
                      />
                    }
                    label="Friday"
                  />
                </Stack>
              </FormGroup>
              <FormHelperText>{multipleDaysRecurrenceError}</FormHelperText>
            </FormControl>
          )}
          <FormControl>
            <Field
              name="endRecurrenceDate"
              component={MyDatePickerField}
              label="End recurrence date"
            />
          </FormControl>
        </>
      )}
    </>
  );
};

export default Recurrence;
