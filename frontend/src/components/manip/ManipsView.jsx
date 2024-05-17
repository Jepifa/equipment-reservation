/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect, useState } from "react";
import { Navigate, Route, Routes } from "react-router-dom";

import {
  useGetManipsByUserQuery,
  useGetManipsQuery,
} from "./manipsSlice.service";

import ManipsRouteElement from "./ManipsRouteElement";

/**
 * ManipsView component to display different views of manips based on user roles.
 * @param {Object} props - The props object.
 * @param {boolean} props.isAdmin - Indicates whether the user is an admin.
 * @param {function} props.setErrorMessage - Function to set error messages.
 * @returns {JSX.Element} - The ManipsView component.
 */
const ManipsView = ({ isAdmin, setErrorMessage }) => {
  const queryHook = isAdmin ? useGetManipsQuery : useGetManipsByUserQuery;
  const {
    data: manips = [],
    isLoading,
    isFetching,
    isError: isGetManipsError,
  } = queryHook(); // Fetching manips data using useGetManipsQuery hook

  const [currentWeekManips, setCurrentWeekManips] = useState([]);
  const [futureManips, setFutureManips] = useState([]);
  const [pastManips, setPastManips] = useState([]);

  useEffect(() => {
    if (isGetManipsError) {
      setErrorMessage(
        "An error occurred while loading manips. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  useEffect(() => {
    const now = new Date();
    const pastManipsArray = [];
    const currentWeekManipsArray = [];
    const futureManipsArray = [];

    manips.forEach((manip) => {
      const startDate = new Date(manip.beginDate);
      const endDate = new Date(manip.endDate);

      if (endDate < now) {
        pastManipsArray.push(manip);
      } else if (startDate > addDays(now, 7)) {
        futureManipsArray.push(manip);
      } else {
        currentWeekManipsArray.push(manip);
      }
    });

    setPastManips(pastManipsArray);
    setCurrentWeekManips(currentWeekManipsArray);
    setFutureManips(futureManipsArray);
  }, [manips]);

  /**
   * Adds days to a date.
   * @param {Date} date - The input date.
   * @param {number} days - The number of days to add.
   * @returns {Date} - The resulting date.
   */
  function addDays(date, days) {
    const result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
  }

  // Render the ManipsView component
  return (
    <>
      <Routes>
        <Route path="" element={<Navigate to={"current-week-manips"} />} />
        <Route
          path="/current-week-manips"
          element={
            <ManipsRouteElement
              isAdmin={isAdmin}
              manips={currentWeekManips}
              isFetching={isFetching}
              isLoading={isLoading}
              title={"Manips of the week"}
              setErrorMessage={setErrorMessage}
            />
          }
        />
        <Route
          path="/future-manips"
          element={
            <ManipsRouteElement
              isAdmin={isAdmin}
              manips={futureManips}
              isFetching={isFetching}
              isLoading={isLoading}
              title={"Next week's manips"}
              setErrorMessage={setErrorMessage}
            />
          }
        />
        <Route
          path="/past-manips"
          element={
            <ManipsRouteElement
              isAdmin={isAdmin}
              manips={pastManips}
              isFetching={isFetching}
              isLoading={isLoading}
              title={"Past manips"}
              setErrorMessage={setErrorMessage}
            />
          }
        />
      </Routes>
    </>
  );
};

export default ManipsView;
