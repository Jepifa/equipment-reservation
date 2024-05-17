/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useState } from "react";

import { Box, Button, Grid, Stack } from "@mui/material";

import DayView from "./DayView";
import Filters from "./Filters";
import MonthView from "./MonthView";
import SelectNbDisplays from "./SelectNbDisplays";
import WeekView from "./WeekView";

/**
 * Calendar component for displaying events in various views (day, week, month).
 * @param {Object} props - Component props.
 * @param {Array} props.manips - Array of manipulations/events.
 * @param {Function} props.handleManipClick - Function to handle manipulation click.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - Calendar component.
 */
const Calendar = ({ manips, handleManipClick, setErrorMessage }) => {
  const [currentDate, setCurrentDate] = useState(new Date());
  const [filteredManips, setFilteredManips] = useState(manips);
  const [maxIndex, setMaxIndex] = useState(4);
  const [view, setView] = useState("week");

  /**
   * Handles changing the view mode of the calendar.
   * @param {string} newView - New view mode ('day', 'week', 'month').
   */
  const handleViewChange = (newView) => {
    setView(newView);
  };

  /**
   * Handles changing the number of displays.
   * @param {Object} event - Change event object.
   */
  const handleNbDisplaysChange = (event) => {
    setMaxIndex(event.target.value);
  };

  /**
   * Checks if a given date is today.
   * @param {Date} date - Date object to check.
   * @returns {boolean} - True if the date is today, false otherwise.
   */
  function isToday(date) {
    const today = new Date();
    return (
      date.getFullYear() === today.getFullYear() &&
      date.getMonth() === today.getMonth() &&
      date.getDate() === today.getDate()
    );
  }

  return (
    <div className="p-4 w-full">
      <Grid container spacing={2}>
        {/* Today Button */}
        <Grid item xs={6}>
          <div className="flex justify-start mb-4 ml-16">
            <Button
              variant={isToday(currentDate) ? "contained" : "outlined"}
              color="primary"
              onClick={() => setCurrentDate(new Date())}
            >
              Today
            </Button>
          </div>
        </Grid>
        {/* View Buttons */}
        <Grid item xs={6}>
          <div className="flex justify-end items-center mb-4 space-x-4 mr-16">
            <Button
              variant={view === "day" ? "contained" : "outlined"}
              color="primary"
              onClick={() => handleViewChange("day")}
            >
              Day
            </Button>
            <Button
              variant={view === "week" ? "contained" : "outlined"}
              color="primary"
              onClick={() => handleViewChange("week")}
            >
              Week
            </Button>
            <Button
              variant={view === "month" ? "contained" : "outlined"}
              color="primary"
              onClick={() => handleViewChange("month")}
            >
              Month
            </Button>
          </div>
        </Grid>
      </Grid>

      {/* Display Select and Filters */}
      <Stack
        direction="row"
        justifyContent="space-between"
        alignItems="center"
        mt={2}
        mb={2}
      >
        <SelectNbDisplays
          name={"selectNbDisplays"}
          label={"Select number of displays"}
          handleChange={handleNbDisplaysChange}
          maxIndex={maxIndex}
          sx={{ width: 300, mr: 2 }}
        />
        <Box sx={{ flexGrow: 1 }} />
        <Filters
          setFilteredManips={setFilteredManips}
          manips={manips}
          setErrorMessage={setErrorMessage}
        />
      </Stack>

      {/* Render different views based on selected view mode */}
      {view === "day" && (
        <DayView
          manips={filteredManips}
          currentDate={currentDate}
          setCurrentDate={setCurrentDate}
          isToday={isToday}
          handleManipClick={handleManipClick}
          maxIndex={maxIndex}
        />
      )}
      {view === "week" && (
        <WeekView
          manips={filteredManips}
          currentDate={currentDate}
          setCurrentDate={setCurrentDate}
          isToday={isToday}
          handleManipClick={handleManipClick}
          maxIndex={maxIndex}
        />
      )}
      {view === "month" && (
        <MonthView
          manips={filteredManips}
          currentDate={currentDate}
          setCurrentDate={setCurrentDate}
          isToday={isToday}
          handleManipClick={handleManipClick}
          maxIndex={maxIndex}
        />
      )}
    </div>
  );
};

export default Calendar;
