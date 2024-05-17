/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect } from "react";

import { FormControl, Stack } from "@mui/material";
import { Formik, Form, Field } from "formik";
import * as Yup from "yup";

import {
  useCreateEquipmentGroupMutation,
  useUpdateEquipmentGroupMutation,
} from "./equipmentGroupsSlice.service";
import { useGetCategoriesQuery } from "../category/categoriesSlice.service";

import MySelectField from "../../util/form_components/MySelectField";
import MySubmitButton from "../../util/form_components/MySubmitButton";
import MyTextField from "../../util/form_components/MyTextField";

/**
 * Component for equipment groups form.
 * @param {Object} props - Component props.
 * @param {Object} props.equipmentGroup - Equipment group object.
 * @param {Function} props.onClose - Function to close the form.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} The JSX representing the equipment groups form component.
 */
const EquipmentGroupsForm = ({ equipmentGroup, onClose, setErrorMessage }) => {
  const { data: categories = [], isError: isGetCategoriesError } =
    useGetCategoriesQuery(); // Fetch categories data using custom hook

  const [createEquipmentGroup] = useCreateEquipmentGroupMutation(); // Destructure mutation hook for creating equipment groups
  const [updateEquipmentGroup] = useUpdateEquipmentGroupMutation(); // Destructure mutation hook for updating equipment groups

  useEffect(() => {
    if (isGetCategoriesError) {
      setErrorMessage(
        "An error occurred while loading categories. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  // Initialize form values
  const initialValues = {
    name: equipmentGroup ? equipmentGroup.name : "", // If equipment group exists, use its name as initial value
    categoryId: equipmentGroup ? equipmentGroup.categoryId : "", // If equipment group exists, use its category ID as initial value
  };

  // Define form validation schema
  const validationSchema = Yup.object().shape({
    name: Yup.string().required("The name is required"), // Define validation for name field
    categoryId: Yup.number().required("The category is required"), // Define validation for category ID field
  });

  /**
   * Handle form submission.
   * @param {Object} values - The form values.
   * @param {Object} actions - The formik form actions.
   * @returns {Promise<void>} - A promise representing the async submission process.
   */
  const onSubmit = async (values, actions) => {
    try {
      if (equipmentGroup) {
        // If equipment group exists, update
        const updatedEquipmentGroup = { ...equipmentGroup, ...values };
        await updateEquipmentGroup(updatedEquipmentGroup).unwrap();
      } else {
        // Else, create new equipment group
        await createEquipmentGroup(values).unwrap();
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
            {/* Field for equipment group name */}
            <FormControl>
              <Field name="name" component={MyTextField} label="Name" />
            </FormControl>
            {/* Field for equipment group category */}
            <FormControl>
              <Field
                name="categoryId"
                component={MySelectField}
                label="Category"
                items={categories}
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

export default EquipmentGroupsForm; // Export EquipmentGroupsForm component
