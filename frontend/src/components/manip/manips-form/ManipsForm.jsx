/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect, useState } from "react";
import dayjs from "dayjs";

import { FormControl, MenuItem, Stack, TextField } from "@mui/material";
import { Formik, Form, Field } from "formik";
import * as Yup from "yup";

import {
  useGetOtherUsersQuery,
  useGetUsersQuery,
} from "../../users/usersSlice.service";
import {
  useCreateManipMutation,
  useUpdateManipMutation,
} from "../manipsSlice.service";
import { useGetPreferencesByUserQuery } from "../preference/preferencesSlice.service";

import MyDateTimePickerField from "../../util/form_components/MyDateTimePickerField";
import MyEquipmentSelectField from "../../util/form_components/MyEquipmentSelectField";
import MyLocationSelectField from "../../util/form_components/MyLocationSelectField";
import MyMultipleSelectField from "../../util/form_components/MyMultipleSelectField";
import MySelectField from "../../util/form_components/MySelectField";
import MySubmitButton from "../../util/form_components/MySubmitButton";
import MyTextField from "../../util/form_components/MyTextField";
import Recurrence from "./Recurrence";

/**
 * Manipulation form component.
 * @param {Object} props - The props object.
 * @param {Object} props.manip - The manipulation object to edit (optional).
 * @param {Function} props.onClose - Function to close the form.
 * @param {Function} props.handleTabUpdated - Function to handle tab update (optional).
 * @param {boolean} props.isAdmin - Boolean indicating if user is an admin.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - The ManipsForm component.
 */
const ManipsForm = ({
  manip,
  onClose,
  handleTabUpdated,
  isAdmin,
  setErrorMessage,
}) => {
  // Fetch users data using custom hook based on admin status
  const queryHook = isAdmin ? useGetUsersQuery : useGetOtherUsersQuery;
  const { data: users = [], isError: isGetUsersError } = queryHook(); // Fetch users data using custom hook
  // Fetch preferences data using custom hook
  const { data: preferences = [], isError: isGetPreferencesError } =
    useGetPreferencesByUserQuery();

  const [createManip] = useCreateManipMutation(); // Destructure mutation hook for creating manips
  const [updateManip] = useUpdateManipMutation(); // Destructure mutation hook for updating manips

  // State for multiple days recurrence error
  const [multipleDaysRecurrenceError, setMultipleDaysRecurrenceError] =
    useState("");
  // State for preference ID
  const [preferenceId, setPreferenceId] = useState(0);
  // State for selected recurrence
  const [selectedRecurrence, setSelectedRecurrence] = useState("");
  // State for selected weekly recurrence
  const [selectedWeeklyRecurrence, setSelectedWeeklyRecurrence] = useState("");

  // State for multiple days recurrence
  const [multipleDaysRecurrence, setMultipleDaysRecurrence] = useState({
    monday: false,
    tuesday: false,
    wednesday: false,
    thursday: false,
    friday: false,
  });

  useEffect(() => {
    if (isGetUsersError) {
      setErrorMessage(
        "An error occurred while loading users. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
    if (isGetPreferencesError) {
      setErrorMessage(
        "An error occurred while loading preferences. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  // Initialize form values
  const defaultInitialValues = {
    name: manip ? manip.name : "",
    locationId: manip ? manip.locationId : "",
    equipmentIds: manip
      ? manip.equipments.map((equipment) => equipment.id)
      : [],
    teamIds: manip ? manip.team.map((user) => user.id) : [],
    beginDate: manip ? dayjs(manip.beginDate) : null,
    endDate: manip ? dayjs(manip.endDate) : null,
    endRecurrenceDate: null,
  };

  const [initialValues, setInitialValues] = useState(
    isAdmin
      ? { userId: manip ? manip.userId : "", ...defaultInitialValues }
      : defaultInitialValues
  );

  // Define form validation schema
  const baseValidationSchema = Yup.object().shape({
    name: Yup.string().required("The name is required"),
    locationId: Yup.number().required("The location is required"),
    equipmentIds: Yup.array().min(1, "At least one equipment is required"),
    beginDate: Yup.date()
      .test(
        "is-after-7am",
        "The begin date cannot be earlier than 7:00 AM",
        function (value) {
          const date = dayjs(value);
          const hour = date.hour();
          return hour >= 7;
        }
      )
      .required("The begin date is required"),
    endDate: Yup.date()
      .required("The end date is required")
      .min(Yup.ref("beginDate"), "End date must be after begin date"),
  });

  const validationSchema = isAdmin
    ? baseValidationSchema.shape({
        userId: Yup.string().required("The pro is required"),
      })
    : baseValidationSchema;

  /**
   * Handle form submission.
   * @param {Object} values - The form values.
   * @param {Object} actions - The formik form actions.
   * @returns {Promise<void>} - A promise representing the async submission process.
   */
  const onSubmit = async (values, actions) => {
    try {
      // Format the beginDate if it exists in the values
      if (values.beginDate) {
        values.beginDate = dayjs(values.beginDate).format(
          "YYYY-MM-DD HH:mm:ss"
        );
      }
      if (values.endDate) {
        values.endDate = dayjs(values.endDate).format("YYYY-MM-DD HH:mm:ss");
      }

      if (manip) {
        // If manip exists, update
        await updateManip({
          ...manip,
          ...values,
        }).unwrap();

        if (handleTabUpdated) {
          handleTabUpdated();
        }
      } else {
        const endRecurrenceDateFormatted = values.endRecurrenceDate
          ? values.endRecurrenceDate.format("YYYY-MM-DD")
          : null;
        if (
          selectedWeeklyRecurrence &&
          !Object.values(multipleDaysRecurrence).some((value) => value === true)
        ) {
          setMultipleDaysRecurrenceError("At least one day must be chosen");
          return;
        }
        // Else, create new manip
        await createManip({
          selectedRecurrence,
          selectedWeeklyRecurrence,
          multipleDaysRecurrence,
          endRecurrenceDateFormatted,
          ...values,
        }).unwrap();
      }
      if (!handleTabUpdated) {
        onClose(); // Close form after submission
      }
    } catch (error) {
      if (error.status === 422) {
        // eslint-disable-next-line no-prototype-builtins
        if (error.data.errors?.hasOwnProperty("multipleDaysRecurrence")) {
          setMultipleDaysRecurrenceError(
            error.data.errors.multipleDaysRecurrence
          );
        }
        actions.setErrors(error.data.errors);
      } // Set errors if there is an error during submission
      else {
        setErrorMessage(
          "An error occurred while submitting the form. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
        );
      }
    }
  };

  /**
   * Handle preference change.
   * @param {Object} event - The event object.
   */
  const handlePreferenceChange = (event) => {
    const preference = preferences.find(
      (preference) => preference.id === event.target.value
    );
    setInitialValues({
      name: preference ? preference.manipName : "",
      locationId: preference ? preference.locationId : "",
      equipmentIds: preference
        ? preference.equipments.map((equipment) => equipment.id)
        : [],
      teamIds: preference ? preference.team.map((user) => user.id) : [],
      beginDate: manip ? dayjs(manip.beginDate) : null,
      endDate: manip ? dayjs(manip.endDate) : null,
      endRecurrenceDate: null,
    });

    setPreferenceId(event.target.value);
  };

  return (
    <Formik // Use Formik to handle form and validation
      initialValues={initialValues}
      enableReinitialize
      validationSchema={validationSchema}
      onSubmit={onSubmit}
    >
      {(
        { isSubmitting } // Render function with functions provided by Formik
      ) => (
        <div style={{ maxHeight: "calc(100vh - 100px)", overflowY: "auto" }}>
          <Form className="space-y-4">
            {/* Form definition */}
            <Stack direction="column" sx={{ mt: 2 }} spacing={2}>
              {/* Vertical stack of fields */}
              {isAdmin ? (
                <FormControl>
                  <Field
                    name="userId"
                    component={MySelectField}
                    label="Pro"
                    items={users}
                  />
                </FormControl>
              ) : (
                !manip && (
                  <TextField
                    name="preference"
                    variant="outlined"
                    select
                    label="Use preference ?"
                    autoComplete="off"
                    value={preferenceId}
                    onChange={handlePreferenceChange}
                    fullWidth
                  >
                    <MenuItem value={0}>No preference</MenuItem>
                    {preferences.map((preference) => (
                      <MenuItem key={preference.id} value={preference.id}>
                        {preference.name}
                      </MenuItem>
                    ))}
                  </TextField>
                )
              )}
              {/* Field for manip name */}
              <FormControl>
                <Field name="name" component={MyTextField} label="Name" />
              </FormControl>
              {/* Field for manip equipment */}
              <FormControl>
                <Field
                  name="equipmentIds"
                  component={MyEquipmentSelectField}
                  setErrorMessage={setErrorMessage}
                  label="Equipment"
                />
              </FormControl>
              {/* Field for manip location */}
              <FormControl>
                <Field
                  name="locationId"
                  component={MyLocationSelectField}
                  setErrorMessage={setErrorMessage}
                  label="Location"
                />
              </FormControl>
              {/* Field for manip team */}
              <FormControl>
                <Field
                  name="teamIds"
                  component={MyMultipleSelectField}
                  label="Team"
                  items={users}
                />
              </FormControl>
              <FormControl>
                <Field
                  name="beginDate"
                  component={MyDateTimePickerField}
                  label="Begin date"
                />
              </FormControl>
              <FormControl>
                <Field
                  name="endDate"
                  component={MyDateTimePickerField}
                  label="End date"
                />
              </FormControl>
              {!manip && (
                <Recurrence
                  selectedRecurrence={selectedRecurrence}
                  setSelectedRecurrence={setSelectedRecurrence}
                  selectedWeeklyRecurrence={selectedWeeklyRecurrence}
                  setSelectedWeeklyRecurrence={setSelectedWeeklyRecurrence}
                  multipleDaysRecurrence={multipleDaysRecurrence}
                  setMultipleDaysRecurrence={setMultipleDaysRecurrence}
                  multipleDaysRecurrenceError={multipleDaysRecurrenceError}
                  setMultipleDaysRecurrenceError={
                    setMultipleDaysRecurrenceError
                  }
                />
              )}
            </Stack>

            {/* Form submission button */}
            <MySubmitButton isSubmitting={isSubmitting}>Submit</MySubmitButton>
          </Form>
        </div>
      )}
    </Formik>
  );
};

export default ManipsForm; // Export ManipsForm component
