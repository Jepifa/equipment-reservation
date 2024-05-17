/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Checkbox, FormControl, FormControlLabel, Stack } from "@mui/material";
import { Formik, Form, Field } from "formik";
import * as Yup from "yup";

import {
  useCreateEquipmentMutation,
  useUpdateEquipmentMutation,
} from "./equipmentSlice.service";

import MyEquipmentGroupSelectField from "../../util/form_components/MyEquipmentGroupSelectField";
import MySubmitButton from "../../util/form_components/MySubmitButton";
import MyTextField from "../../util/form_components/MyTextField";

/**
 * Component for equipment form.
 * @param {Object} props - Component props.
 * @param {Object} props.equipment - Equipment object.
 * @param {Function} props.onClose - Function to close the form.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} The JSX representing the equipment form component.
 */
const EquipmentForm = ({ equipment, onClose, setErrorMessage }) => {
  const [createEquipment] = useCreateEquipmentMutation(); // Destructure mutation hook for creating equipment
  const [updateEquipment] = useUpdateEquipmentMutation(); // Destructure mutation hook for updating equipment

  // Initialize form values
  const initialValues = {
    name: equipment ? equipment.name : "", // If equipment exists, use its name as initial value
    equipmentGroupId: equipment ? equipment.equipmentGroupId : "", // If equipment exists, use its equipment group ID as initial value
    operational: equipment ? equipment.operational : true, // If equipment exists, use its operational value as initial value
  };

  // Define form validation schema
  const validationSchema = Yup.object().shape({
    name: Yup.string().required("The name is required"), // Define validation for name field
    equipmentGroupId: Yup.number().required("The equipment group is required"), // Define validation for equipment group ID field
  });

  /**
   * Handle form submission.
   * @param {Object} values - The form values.
   * @param {Object} actions - The formik form actions.
   * @returns {Promise<void>} - A promise representing the async submission process.
   */
  const onSubmit = async (values, actions) => {
    try {
      if (equipment) {
        // If equipment exists, update
        const updatedEquipment = { ...equipment, ...values };
        await updateEquipment(updatedEquipment).unwrap();
      } else {
        // Else, create new equipment
        await createEquipment(values).unwrap();
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
        { isSubmitting, handleChange, values } // Render function with functions provided by Formik
      ) => (
        <Form className="space-y-4">
          {" "}
          {/* Form definition */}
          <Stack direction="column" spacing={2}>
            {" "}
            {/* Vertical stack of fields */}
            {/* Field for equipment name */}
            <FormControl>
              <Field name="name" component={MyTextField} label="Name" />
            </FormControl>
            {/* Field for equipment group */}
            <FormControl>
              <Field
                name="equipmentGroupId"
                component={MyEquipmentGroupSelectField}
                setErrorMessage={setErrorMessage}
                label="Equipment group"
              />
            </FormControl>
            {/* Field for operational */}
            <FormControlLabel
              label="Operational"
              sx={{ justifyContent: "center" }}
              control={
                <Field
                  name="operational"
                  type="checkbox"
                  as={Checkbox}
                  onChange={handleChange}
                  checked={values.operational}
                />
              }
            />
          </Stack>
          {/* Form submission button */}
          <MySubmitButton isSubmitting={isSubmitting}>Submit</MySubmitButton>
        </Form>
      )}
    </Formik>
  );
};

export default EquipmentForm; // Export EquipmentForm component
