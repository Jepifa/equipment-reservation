/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect } from "react";

import { FormControl, Stack } from "@mui/material";
import { Formik, Form, Field } from "formik";
import * as Yup from "yup";

import { useGetOtherUsersQuery } from "../../users/usersSlice.service";
import {
  useCreatePreferenceMutation,
  useUpdatePreferenceMutation,
} from "./preferencesSlice.service";

import MyEquipmentSelectField from "../../util/form_components/MyEquipmentSelectField";
import MyLocationSelectField from "../../util/form_components/MyLocationSelectField";
import MyMultipleSelectField from "../../util/form_components/MyMultipleSelectField";
import MySubmitButton from "../../util/form_components/MySubmitButton";
import MyTextField from "../../util/form_components/MyTextField";

/**
 * PreferencesForm component for manipulating preferences.
 * @param {Object} props - The props object.
 * @param {Object} props.preference - The preference object.
 * @param {Function} props.onClose - Function to close the form.
 * @param {Function} props.handleTabUpdated - Function to handle tab update.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - The PreferencesForm component.
 */
const PreferencesForm = ({
  preference,
  onClose,
  handleTabUpdated,
  setErrorMessage,
}) => {
  const { data: users = [], isError: isGetUsersError } =
    useGetOtherUsersQuery(); // Fetch users data using custom hook

  const [createPreference] = useCreatePreferenceMutation(); // Destructure mutation hook for creating preferences
  const [updatePreference] = useUpdatePreferenceMutation(); // Destructure mutation hook for updating preferences

  useEffect(() => {
    if (isGetUsersError) {
      setErrorMessage(
        "An error occurred while loading users. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  // Initialize form values
  const initialValues = {
    name: preference ? preference.name : "", // If preference exists, use its name as initial value
    manipName: preference ? preference.manipName : "", // If preference exists, use its manipName as initial value
    locationId: preference ? preference.locationId : "", // If preference exists, use its location ID as initial value
    equipmentIds: preference
      ? preference.equipments.map((equipment) => equipment.id)
      : [], // If preference exists, use its equipment IDs as initial value
    teamIds: preference ? preference.team.map((user) => user.id) : [], // If preference exists, use its user IDs as initial value
  };

  // Define form validation schema
  const validationSchema = Yup.object().shape({
    name: Yup.string().required("The name is required"), // Define validation for name field
    manipName: Yup.string().required("The manip name is required"), // Define validation for manip name field
    locationId: Yup.number().required("The location is required"), // Define validation for location ID field
    equipmentIds: Yup.array().min(1, "At least one equipment is required"), // Define validation for equipment IDs field
  });

  /**
   * Handle form submission.
   * @param {Object} values - The form values.
   * @param {Object} actions - The formik form actions.
   * @returns {Promise<void>} - A promise representing the async submission process.
   */
  const onSubmit = async (values, actions) => {
    try {
      if (preference) {
        // If preference exists, update
        await updatePreference({
          ...preference,
          ...values,
        }).unwrap();

        if (handleTabUpdated) {
          handleTabUpdated();
        }
      } else {
        // Else, create new preference
        await createPreference(values).unwrap();
      }
      onClose(); // Close form after submission
    } catch (error) {
      if (error.status === 422) {
        actions.setErrors(error.data.errors);
      } // Set errors if there is an error during submission
      else {
        setErrorMessage(
          "An error occurred while submitting the form. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
        );
      }
    }
  };

  return (
    <Formik // Use Formik to handle form and validation
      initialValues={initialValues}
      validationSchema={validationSchema}
      onSubmit={onSubmit}
    >
      {(
        { isSubmitting } // Render function with functions provided by Formik
      ) => (
        <div style={{ maxHeight: "calc(100vh - 100px)", overflowY: "auto" }}>
          <Form className="space-y-4">
            {/* Form definition */}
            <Stack direction="column" spacing={2}>
              {/* Vertical stack of fields */}
              {/* Field for preference name */}
              <FormControl>
                <Field
                  name="name"
                  component={MyTextField}
                  label="Name"
                  sx={{ mt: 2 }}
                />
              </FormControl>
              {/* Field for manip name */}
              <FormControl>
                <Field
                  name="manipName"
                  component={MyTextField}
                  label="Manip name"
                />
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
            </Stack>
            {/* Form submission button */}
            <MySubmitButton isSubmitting={isSubmitting}>Submit</MySubmitButton>
          </Form>
        </div>
      )}
    </Formik>
  );
};

export default PreferencesForm; // Export PreferencesForm component
