/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useState } from "react";

import { Link, useParams, useSearchParams } from "react-router-dom";

import { Alert, Box, FormControl, Snackbar, Typography } from "@mui/material";
import { Field, Formik, Form } from "formik";
import * as Yup from "yup";

import { useResetPasswordMutation } from "../context/authSlice.service";

import MyPasswordTextField from "../components/util/form_components/MyPasswordTextField";
import MySubmitButton from "../components/util/form_components/MySubmitButton";
import useAuthContext from "../context/AuthContext";

/**
 * Component for resetting user password.
 * @returns {JSX.Element} The JSX representing the reset password component.
 */
const ResetPassword = () => {
  const { csrf } = useAuthContext();

  const [resetPassword] = useResetPasswordMutation();
  const [searchParams] = useSearchParams();

  const [errorMessage, setErrorMessage] = useState(null);
  const [status, setStatus] = useState(null);

  // Initialize form values
  const initialValues = {
    email: searchParams.get("email"),
    token: useParams()["token"],
    password: "",
    password_confirmation: "",
  };

  // Define form validation schema
  const validationSchema = Yup.object().shape({
    password: Yup.string().required("The password is required"),
    password_confirmation: Yup.string().required(
      "The password confirmation is required"
    ),
  });

  /**
   * Function to handle form submission.
   * @param {Object} values - Form values.
   * @param {Object} actions - Formik actions.
   */
  const onSubmit = async (values, actions) => {
    try {
      await csrf;
      const response = await resetPassword(values).unwrap();
      setStatus(response.status);
    } catch (error) {
      console.log(error);
      if (error.response === 422) {
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
      {/* Snackbar for displaying error messages */}
      <Snackbar
        open={!!errorMessage}
        autoHideDuration={6000}
        onClose={() => setErrorMessage(null)}
      >
        <Alert severity="error">{errorMessage}</Alert>
      </Snackbar>
      {/* Snackbar for displaying success status */}
      <Snackbar
        open={!!status}
        autoHideDuration={6000}
        onClose={() => setStatus(null)}
      >
        <Alert severity="success">
          {status}
          <div className="m-2 p-2">
            Go to <Link to="/login">Login</Link>
          </div>
        </Alert>
      </Snackbar>
      {/* Reset password form */}
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
        <Typography variant="h4" sx={{ mb: 4 }}>
          Add your new password
        </Typography>
        {/* Formik form for resetting password */}
        <Formik // Use Formik to handle form and validation
          initialValues={initialValues}
          validationSchema={validationSchema}
          onSubmit={onSubmit}
        >
          {({ isSubmitting }) => (
            <Form className="space-y-4">
              {/* Field for password */}
              <FormControl style={{ width: "100%" }}>
                <Field
                  name="password"
                  component={MyPasswordTextField}
                  label="Password"
                />
              </FormControl>
              {/* Field for password confirmation */}
              <FormControl style={{ width: "100%" }}>
                <Field
                  name="password_confirmation"
                  component={MyPasswordTextField}
                  label="Password Confirmation"
                />
              </FormControl>
              {/* Submit button */}
              <MySubmitButton isSubmitting={isSubmitting}>
                Reset password
              </MySubmitButton>
            </Form>
          )}
        </Formik>
      </Box>
    </>
  );
};

export default ResetPassword;
