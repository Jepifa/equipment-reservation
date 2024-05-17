/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useState } from "react";

import { Link } from "react-router-dom";

import { Alert, Box, FormControl, Snackbar, Typography } from "@mui/material";
import { Field, Form, Formik } from "formik";
import * as Yup from "yup";

import MyPasswordTextField from "../components/util/form_components/MyPasswordTextField";
import MySubmitButton from "../components/util/form_components/MySubmitButton";
import MyTextField from "../components/util/form_components/MyTextField";
import useAuthContext from "../context/AuthContext";

/**
 * Component for user login.
 * @returns {JSX.Element} The JSX representing the login component.
 */
const Login = () => {
  const { login } = useAuthContext();

  const [errorMessage, setErrorMessage] = useState(null);

  // Initialize form values
  const initialValues = {
    email: "",
    password: "",
  };

  // Define form validation schema
  const validationSchema = Yup.object().shape({
    email: Yup.string().required("The email is required"),
    password: Yup.string().required("The password is required"),
  });

  /**
   * Function to handle form submission.
   * @param {Object} values - Form values.
   * @param {Object} actions - Formik actions.
   */
  const onSubmit = async (values, actions) => {
    try {
      await login(values);
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
      {/* Snackbar for displaying error messages */}
      <Snackbar
        open={!!errorMessage}
        autoHideDuration={6000}
        onClose={() => setErrorMessage(null)}
      >
        <Alert severity="error">{errorMessage}</Alert>
      </Snackbar>
      {/* Login form */}
      <Box
        sx={{
          position: "absolute",
          top: "50%",
          left: "50%",
          transform: "translate(-50%, -50%)",
          bgcolor: "background.paper",
          boxShadow: 24,
          p: 4,
          maxWidth: 600,
          width: "90%",
        }}
      >
        <Typography variant="h4" sx={{ mb: 4 }}>
          Equipment Reservation
        </Typography>
        {/* Formik form for login */}
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
              </FormControl>{" "}
              {/* Field for password */}
              <FormControl style={{ width: "100%" }}>
                <Field
                  name="password"
                  component={MyPasswordTextField}
                  label="Password"
                />
              </FormControl>
              {/* Submit button */}
              <MySubmitButton isSubmitting={isSubmitting}>Login</MySubmitButton>
            </Form>
          )}
        </Formik>
        {/* Link to forgot password */}
        <Link
          to="/forgot-password"
          className="
            flex
            justify-center
            mb-2
            mt-6
            text-base text-[#adadad]
            hover:text-primary hover:underline
        "
        >
          Forgot Password?
        </Link>
        {/* Link to sign up */}
        <p className=" flex justify-center text-base text-[#adadad]">
          Not a member yet?&nbsp;
          <Link to="/register" className="text-primary hover:underline">
            Sign Up
          </Link>
        </p>
      </Box>
    </>
  );
};

export default Login;
