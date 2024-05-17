/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import CircularProgress from "@mui/material/CircularProgress";

/**
 * A loading page component displaying a CircularProgress spinner.
 * @returns {JSX.Element} Loading page component.
 */
const LoadingPage = () => {
  return (
    <div
      style={{
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        height: "100vh",
      }}
    >
      <CircularProgress />
    </div>
  );
};

export default LoadingPage;
