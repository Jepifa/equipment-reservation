/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useState } from "react";

import { Alert, Box, FormControl, Snackbar, Typography } from "@mui/material";
import { Field, Formik, Form } from "formik";
import * as Yup from "yup";

import { useForgotPasswordMutation } from "../context/authSlice.service";

import MySubmitButton from "../components/util/form_components/MySubmitButton";
import MyTextField from "../components/util/form_components/MyTextField";
import useAuthContext from "../context/AuthContext";

/**
 * Component for password reset request.
 * @returns {JSX.Element} The JSX representing the password reset request component.
 */
const ForgotPassword = () => {
  const { csrf } = useAuthContext();

  const [forgotPassword] = useForgotPasswordMutation();

  const [status, setStatus] = useState(null);
  const [errorMessage, setErrorMessage] = useState(null);

  // Initialize form values
  const initialValues = {
    email: "",
  };

  // Define form validation schema
  const validationSchema = Yup.object().shape({
    email: Yup.string().required("The email is required"),
  });

  /**
   * Function to handle form submission.
   * @param {Object} values - Form values.
   * @param {Object} actions - Formik actions.
   */
  const onSubmit = async (values, actions) => {
    try {
      await csrf;
      const response = await forgotPassword(values).unwrap();
      setStatus(response.status);
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
    <>
      {/* Snackbar for displaying success message */}
      <Snackbar
        open={!!status}
        autoHideDuration={6000}
        onClose={() => setStatus(null)}
      >
        <Alert severity="success">{status}</Alert>
      </Snackbar>
      {/* Snackbar for displaying error messages */}
      <Snackbar
        open={!!errorMessage}
        autoHideDuration={6000}
        onClose={() => setErrorMessage(null)}
      >
        <Alert severity="error">{errorMessage}</Alert>
      </Snackbar>
      {/* Password reset form */}
      <Box
        sx={{
          position: "absolute",
          top: "40%",
          left: "50%",
          transform: "translate(-50%, -50%)",
          bgcolor: "background.paper",
          boxShadow: 24,
          p: 4,
          maxWidth: 540,
          width: "100%",
        }}
      >
        <Typography variant="h6" gutterBottom sx={{ mb: 2 }}>
          Forgot your password? Let us know your email address and we will email
          you a password reset link.
        </Typography>
        {/* Formik form for password reset */}
        <Formik // Use Formik to handle form and validation
          initialValues={initialValues}
          validationSchema={validationSchema}
          onSubmit={onSubmit}
        >
          {({ isSubmitting }) => (
            <Form className="space-y-4">
              {/* Field for email */}
              <FormControl style={{ width: "100%" }}>
                <Field name="email" component={MyTextField} label="Email" />
              </FormControl>
              {/* Submit button */}
              <MySubmitButton isSubmitting={isSubmitting}>
                Submit
              </MySubmitButton>
            </Form>
          )}
        </Formik>
      </Box>
    </>
  );
};

export default ForgotPassword;
