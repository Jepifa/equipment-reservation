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
 * Component for user registration.
 * @returns {JSX.Element} The JSX representing the registration component.
 */
const Register = () => {
  const { register } = useAuthContext();

  const [errorMessage, setErrorMessage] = useState(null);

  // Initialize form values
  const initialValues = {
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  };

  // Define form validation schema
  const validationSchema = Yup.object().shape({
    name: Yup.string().required("The name is required"),
    email: Yup.string().required("The email is required"),
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
      await register(values);
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
      {/* Registration form */}
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
        {/* Formik form for registration */}
        <Formik // Use Formik to handle form and validation
          initialValues={initialValues}
          validationSchema={validationSchema}
          onSubmit={onSubmit}
        >
          {({ isSubmitting }) => (
            <Form className="space-y-4">
              {/* Field for name */}
              <FormControl style={{ width: "100%" }}>
                <Field name="name" component={MyTextField} label="Name" />
              </FormControl>
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
                Register
              </MySubmitButton>
            </Form>
          )}
        </Formik>
        {/* Link to sign in */}
        <p className=" flex justify-center text-base text-[#adadad] mt-6">
          Already member?&nbsp;
          <Link to="/login" className="text-primary hover:underline">
            Sign In
          </Link>
        </p>
      </Box>
    </>
  );
};

export default Register;
