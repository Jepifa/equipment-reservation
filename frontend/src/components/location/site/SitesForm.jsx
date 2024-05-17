/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { FormControl } from "@mui/material";
import { Formik, Form, Field } from "formik";
import * as Yup from "yup";

import {
  useCreateSiteMutation,
  useUpdateSiteMutation,
} from "./sitesSlice.service";

import MySubmitButton from "../../util/form_components/MySubmitButton";
import MyTextField from "../../util/form_components/MyTextField";

/**
 * Component for rendering a form to create or update Sites.
 * @param {Object} props - The component props.
 * @param {Object} props.site - The site object to be updated (optional).
 * @param {Function} props.onClose - The callback function to close the form.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - The rendered component.
 */
const SitesForm = ({ site, onClose, setErrorMessage }) => {
  const [createSite] = useCreateSiteMutation();
  const [updateSite] = useUpdateSiteMutation();

  // Initialize form values
  const initialValues = {
    name: site ? site.name : "",
  };

  // Define form validation schema
  const validationSchema = Yup.object().shape({
    name: Yup.string().required("The name is required"),
  });

  /**
   * Handle form submission.
   * @param {Object} values - The form values.
   * @param {Object} actions - The formik form actions.
   * @returns {Promise<void>} - A promise representing the async submission process.
   */
  const onSubmit = async (values, actions) => {
    try {
      const { name } = values;
      if (site) {
        const updatedSite = { ...site, name };
        await updateSite(updatedSite).unwrap();
      } else {
        await createSite({ name }).unwrap();
      }
      onClose();
    } catch (error) {
      if (error.status === 422) {
        actions.setErrors(error.data.errors);
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
      {({ isSubmitting }) => (
        <Form className="space-y-4">
          {/* Field for site name */}
          <FormControl style={{ width: "100%" }}>
            <Field name="name" component={MyTextField} label="Name" />
          </FormControl>
          {/* Submit button */}
          <MySubmitButton isSubmitting={isSubmitting}>Submit</MySubmitButton>
        </Form>
      )}
    </Formik>
  );
};

export default SitesForm;
