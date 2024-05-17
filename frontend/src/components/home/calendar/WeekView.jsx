/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import ManipWeekView from "./ManipWeekView";
import TimeNavBar from "./TimeNavBar";

/**
 * WeekView component displays a weekly view with days and hours, along with manipulations for each day.
 * @param {Object} props - The props object containing the following properties:
 * @param {Date} props.currentDate - The current date displayed in the week view.
 * @param {Function} props.setCurrentDate - A function to set the current date.
 * @param {Function} props.isToday - A function to check if a given date is today.
 * @param {Array<Object>} props.manips - An array of manipulation objects for the week.
 * @param {Function} props.handleManipClick - A function to handle manipulation click events.
 * @param {number} props.maxIndex - The maximum index value for manipulations.
 * @returns {JSX.Element} The WeekView component JSX.
 */
const WeekView = ({
  currentDate,
  setCurrentDate,
  isToday,
  manips,
  handleManipClick,
  maxIndex,
}) => {
  /**
   * Moves the view to the previous week.
   */
  const prevWeek = () => {
    setCurrentDate((prevDate) => {
      const prevWeekDate = new Date(prevDate);
      prevWeekDate.setDate(prevWeekDate.getDate() - 7);
      return prevWeekDate;
    });
  };

  /**
   * Moves the view to the next week.
   */
  const nextWeek = () => {
    setCurrentDate((prevDate) => {
      const nextWeekDate = new Date(prevDate);
      nextWeekDate.setDate(nextWeekDate.getDate() + 7);
      return nextWeekDate;
    });
  };

  /**
   * Generates an array of dates representing the days in the current week.
   * @returns {Date[]} Array of dates representing the days in the current week.
   */
  const getDaysInWeek = () => {
    const days = [];
    const firstDay = new Date(currentDate);
    firstDay.setDate(firstDay.getDate() - firstDay.getDay() + 1); // Get the first day of the week
    for (let i = 0; i < 7; i++) {
      const day = new Date(firstDay);
      day.setDate(day.getDate() + i);
      days.push(day);
    }
    return days;
  };

  /**
   * Converts the current date range to a string format.
   * @returns {string} The formatted date string representing the current date range.
   */
  const dateToString = () => {
    return (
      daysInWeek[0].toLocaleDateString("en-US", {
        month: "long",
        day: "numeric",
      }) +
      " - " +
      daysInWeek[6].toLocaleString("en-US", {
        month: "long",
        day: "numeric",
      }) +
      ", " +
      currentDate.toLocaleString("en-US", {
        year: "numeric",
      })
    );
  };

  /**
   * Filters and retrieves manipulations within the current week.
   * @returns {Object[]} Array of manipulation objects within the current week.
   */
  const getManipsInWeek = () => {
    const firstDayOfWeek = daysInWeek[0];
    firstDayOfWeek.setHours(0, 0, 0);
    const lastDayOfWeek = daysInWeek[6];
    lastDayOfWeek.setHours(23, 59, 59);

    return manips.filter((manip) => {
      const manipStartDate = new Date(manip.beginDate);
      const manipEndDate = new Date(manip.endDate);

      const manipStartsInWeek =
        manipStartDate >= firstDayOfWeek && manipStartDate <= lastDayOfWeek;
      const manipEndsInWeek =
        manipEndDate >= firstDayOfWeek && manipEndDate <= lastDayOfWeek;
      const manipStartsBeforeAndEndsAfter =
        manipStartDate < firstDayOfWeek && manipEndDate > lastDayOfWeek;

      return (
        manipStartsInWeek || manipEndsInWeek || manipStartsBeforeAndEndsAfter
      );
    });
  };

  const daysInWeek = getDaysInWeek();

  const hoursOfDay = Array.from({ length: 24 }, (_, i) => i);

  return (
    <div>
      {/* Time navigation bar */}
      <TimeNavBar
        onPrevClick={prevWeek}
        date={dateToString()}
        onNextClick={nextWeek}
      ></TimeNavBar>

      <div className="relative">
        {/* Grid layout for days and hours */}
        <div className="grid grid-cols-8 text-center">
          <div className="grid grid-rows-13 text-center">
            {/* Hour labels */}
            <div className="font-semibold h-hhh"></div>
            {hoursOfDay.slice(7, 19).map((hour) => (
              <div key={"h" + hour}>
                {/* Hour labels for each half-hour */}
                <div className="font-semibold border-t border-gray-300 h-hhh w-full">
                  {hour}:00
                </div>
                <div className="border-t border-gray-200 h-hhh w-full">
                  {hour}:30
                </div>
              </div>
            ))}
          </div>
          {/* Render each day of the week */}
          {daysInWeek.map((day) => (
            <div key={"d" + day.getDay()} className="grid grid-rows-13">
              {/* Day labels */}
              <div
                className={
                  isToday(day)
                    ? "font-semibold h-hhh border-l border-gray-300 bg-gray-400"
                    : "font-semibold h-hhh border-l border-gray-300"
                }
              >
                {day.toLocaleString("en-US", {
                  weekday: "short",
                })}{" "}
                {day.getDate()}
                {"/"}
                {day.getMonth() + 1}
              </div>
              <div>
                {/* Render slots for each half-hour */}
                {hoursOfDay.slice(7, 19).map((hour) => (
                  <div key={day.getDay() + " - " + hour}>
                    <div className="font-semibold border-t border-l border-gray-300 h-hhh w-full"></div>
                    <div className="border-t border-l border-l-gray-300 border-t-gray-200 h-hhh w-full"></div>
                  </div>
                ))}
              </div>
            </div>
          ))}
        </div>
        <div className="absolute top-12 left-0 w-full h-24hhh z-10">
          {getManipsInWeek().map((manip, index) => (
            <ManipWeekView
              key={manip.id}
              manip={manip}
              index={index}
              daysInWeek={daysInWeek}
              handleManipClick={handleManipClick}
              maxIndex={maxIndex}
            />
          ))}
        </div>
      </div>
    </div>
  );
};

export default WeekView;
