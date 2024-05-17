/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import ManipDayView from "./ManipDayView";
import TimeNavBar from "./TimeNavBar";

/**
 * DayView component for displaying events and time slots for a single day.
 * @param {Object} props - Component props.
 * @param {Function} props.setCurrentDate - Function to set the current date.
 * @param {Date} props.currentDate - Current date being displayed.
 * @param {Function} props.isToday - Function to check if a date is today.
 * @param {Array} props.manips - Array of manipulations/events for the day.
 * @param {Function} props.handleManipClick - Function to handle manipulation click.
 * @param {number} props.maxIndex - Maximum index of displayed manipulations.
 * @returns {JSX.Element} - DayView component.
 */
const DayView = ({
  setCurrentDate,
  currentDate,
  isToday,
  manips,
  handleManipClick,
  maxIndex,
}) => {
  /**
   * Handles navigating to the previous day.
   */
  const prevDay = () => {
    setCurrentDate((prevDate) => {
      const prevDayDate = new Date(prevDate);
      prevDayDate.setDate(prevDayDate.getDate() - 1);
      return prevDayDate;
    });
  };

  /**
   * Handles navigating to the next day.
   */
  const nextDay = () => {
    setCurrentDate((prevDate) => {
      const nextDayDate = new Date(prevDate);
      nextDayDate.setDate(nextDayDate.getDate() + 1);
      return nextDayDate;
    });
  };

  /**
   * Generates an array containing hours of the day.
   * @returns {number[]} - Array of hours in a day (0-23).
   */
  const getHoursOfDay = () => {
    return Array.from({ length: 24 }, (_, i) => i);
  };

  /**
   * Filters manipulations/events to include only those occurring on the current day.
   * @returns {Object[]} - Array of manipulations/events for the current day.
   */
  const getManipsInDay = () => {
    const beginCurrentDate = new Date(currentDate);
    beginCurrentDate.setHours(0, 0, 0);
    const endCurrentDate = new Date(currentDate);
    endCurrentDate.setHours(23, 59, 59);

    return manips.filter((manip) => {
      const manipStartDate = new Date(manip.beginDate);
      const manipEndDate = new Date(manip.endDate);

      const manipStartsInDay =
        manipStartDate >= beginCurrentDate && manipStartDate <= endCurrentDate;
      const manipEndsInDay =
        manipEndDate >= beginCurrentDate && manipEndDate <= endCurrentDate;
      const manipStartsBeforeAndEndsAfter =
        manipStartDate < beginCurrentDate && manipEndDate > endCurrentDate;

      return (
        manipStartsInDay || manipEndsInDay || manipStartsBeforeAndEndsAfter
      );
    });
  };

  const hoursOfDay = getHoursOfDay();

  return (
    <div>
      {/* Navigation Bar */}
      <TimeNavBar
        onPrevClick={prevDay}
        date={currentDate.toLocaleString("en-US", {
          month: "long",
          day: "numeric",
          year: "numeric",
        })}
        onNextClick={nextDay}
      ></TimeNavBar>

      {/* Time Slots */}
      <div className="relative">
        <div className="grid grid-cols-8 text-center mb-4">
          <div className="col-span-1">
            <div className="grid grid-rows-13">
              <div className="h-hhh"></div>
              {hoursOfDay.slice(7, 19).map((hour) => (
                <div key={hour}>
                  <div className="font-semibold border-t border-gray-300 h-hhh w-full">
                    {hour}:00
                  </div>
                  <div className="border-t border-gray-200 h-hhh w-full">
                    {hour}:30
                  </div>
                </div>
              ))}
            </div>
          </div>
          <div className="col-span-7">
            <div className="grid grid-rows-13">
              <div
                className={
                  isToday(currentDate)
                    ? "font-semibold h-hhh border-gray-300 bg-gray-400"
                    : "font-semibold h-hhh border-gray-300"
                }
              >
                {currentDate.toLocaleString("en-US", {
                  weekday: "long",
                })}
              </div>
              {hoursOfDay.slice(7, 19).map((hour) => (
                <div key={hour}>
                  <div className="font-semibold border-t border-gray-300 h-hhh w-full"></div>
                  <div className="border-t border-gray-200 h-hhh w-full"></div>
                </div>
              ))}
            </div>
          </div>
        </div>
        {/* Manipulations/Events */}
        <div className="absolute top-12 left-0 w-full h-24hhh z-10">
          {getManipsInDay().map((manip, index) => (
            <ManipDayView
              key={manip.id}
              manip={manip}
              index={index}
              day={currentDate}
              handleManipClick={handleManipClick}
              maxIndex={maxIndex}
            />
          ))}
        </div>
      </div>
    </div>
  );
};

export default DayView;
