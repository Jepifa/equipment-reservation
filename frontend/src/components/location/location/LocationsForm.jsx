/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect } from "react";

import { FormControl, Stack } from "@mui/material";
import { Formik, Form, Field } from "formik";
import * as Yup from "yup";

import { useGetSitesQuery } from "../site/sitesSlice.service";
import {
  useCreateLocationMutation,
  useUpdateLocationMutation,
} from "./locationsSlice.service";

import MySelectField from "../../util/form_components/MySelectField";
import MySubmitButton from "../../util/form_components/MySubmitButton";
import MyTextField from "../../util/form_components/MyTextField";

/**
 * Location form component.
 * @param {Object} props - The props object.
 * @param {Object} props.location - The location object to edit (optional).
 * @param {Function} props.onClose - Function to close the form.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - The LocationForm component.
 */
const LocationsForm = ({ location, onClose, setErrorMessage }) => {
  const { data: sites = [], isError: isGetSitesError } = useGetSitesQuery(); // Fetch sites data using custom hook

  const [createLocation] = useCreateLocationMutation(); // Destructure mutation hook for creating locations
  const [updateLocation] = useUpdateLocationMutation(); // Destructure mutation hook for updating locations

  useEffect(() => {
    if (isGetSitesError) {
      setErrorMessage(
        "An error occurred while loading sites. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  // Initialize form values
  const initialValues = {
    name: location ? location.name : "", // If location exists, use its name as initial value
    siteId: location ? location.siteId : "", // If location exists, use its site ID as initial value
  };

  // Define form validation schema
  const validationSchema = Yup.object().shape({
    name: Yup.string().required("The name is required"), // Define validation for name field
    siteId: Yup.number().required("The site is required"), // Define validation for site ID field
  });

  /**
   * Handle form submission.
   * @param {Object} values - The form values.
   * @param {Object} actions - The formik form actions.
   * @returns {Promise<void>} - A promise representing the async submission process.
   */
  const onSubmit = async (values, actions) => {
    try {
      if (location) {
        // If location exists, update
        const updatedLocation = { ...location, ...values };
        await updateLocation(updatedLocation).unwrap();
      } else {
        // Else, create new location
        await createLocation(values).unwrap();
      }
      onClose(); // Close form after submission
    } catch (error) {
      if (error.status === 422) {
        actions.setErrors(error.data.errors); // Set errors if there is an error during submission
      } else {
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
        <Form className="space-y-4">
          {" "}
          {/* Form definition */}
          <Stack direction="column" spacing={2}>
            {" "}
            {/* Vertical stack of fields */}
            {/* Field for location name */}
            <FormControl>
              <Field name="name" component={MyTextField} label="Name" />
            </FormControl>
            {/* Field for location site */}
            <FormControl>
              <Field
                name="siteId"
                component={MySelectField}
                label="Site"
                items={sites}
              />
            </FormControl>
          </Stack>
          {/* Form submission button */}
          <MySubmitButton isSubmitting={isSubmitting}>Submit</MySubmitButton>
        </Form>
      )}
    </Formik>
  );
};

export default LocationsForm; // Export LocationsForm component
