/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import TimeNavBar from "./TimeNavBar";

/**
 * MonthView component for displaying the calendar view by month.
 * @param {Object} props - Component props.
 * @param {Function} props.setCurrentDate - Function to set the current date.
 * @param {Date} props.currentDate - Current date.
 * @param {Function} props.isToday - Function to check if a date is today.
 * @param {Array} props.manips - Array of manipulations/events.
 * @param {Function} props.handleManipClick - Function to handle manipulation/event click.
 * @param {number} props.maxIndex - Maximum index of displayed manipulations/events.
 * @returns {JSX.Element} - MonthView component.
 */
const MonthView = ({
  setCurrentDate,
  currentDate,
  isToday,
  manips,
  handleManipClick,
  maxIndex,
}) => {
  /**
   * Function to navigate to the previous month.
   */
  const prevMonth = () => {
    setCurrentDate((prevDate) => {
      const prevMonthDate = new Date(prevDate);
      prevMonthDate.setMonth(prevMonthDate.getMonth() - 1);
      return prevMonthDate;
    });
  };

  /**
   * Function to navigate to the next month.
   */
  const nextMonth = () => {
    setCurrentDate((prevDate) => {
      const nextMonthDate = new Date(prevDate);
      nextMonthDate.setMonth(nextMonthDate.getMonth() + 1);
      return nextMonthDate;
    });
  };

  /**
   * Function to get an array of days in the current month.
   * @returns {Array} - Array of day objects.
   */
  const getDaysInMonth = () => {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const numDays = new Date(year, month + 1, 0).getDate();
    const firstDayIndex = new Date(year, month, 1).getDay();
    const lastDayIndex = new Date(year, month, numDays).getDay();
    const days = [];

    for (let i = firstDayIndex; i > 1; i--) {
      days.push({
        day: "",
        date: new Date(year, month, -i + 1),
      });
    }

    for (let i = 1; i <= numDays; i++) {
      days.push({
        day: i,
        date: new Date(year, month, i),
      });
    }

    for (let i = 1; i < 8 - lastDayIndex; i++) {
      days.push({
        day: "",
        date: new Date(year, month + 1, i),
      });
    }

    return days;
  };

  /**
   * Filters manipulations/events for a specific day.
   * @param {Date} date - Date to filter manipulations/events.
   * @returns {Array} - Array of manipulations/events for the specified day.
   */
  const getManipsInDay = (date) => {
    const beginCurrentDate = new Date(date);
    beginCurrentDate.setHours(0, 0, 0);
    const endCurrentDate = new Date(date);
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

  const daysInMonth = getDaysInMonth();

  return (
    <div>
      <TimeNavBar
        onPrevClick={prevMonth}
        date={currentDate.toLocaleString("en-US", {
          month: "long",
          year: "numeric",
        })}
        onNextClick={nextMonth}
      ></TimeNavBar>

      <div className="grid grid-cols-7 gap-2 text-center">
        {["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"].map((day) => (
          <div key={day} className="font-semibold">
            {day}
          </div>
        ))}
        {daysInMonth.map((item, index) => (
          <div
            key={index}
            className={`py-2 relative ${
              item.day
                ? isToday(item.date)
                  ? "bg-gray-400 h-32"
                  : "bg-gray-200 h-32"
                : ""
            }`}
          >
            {item.day}
            {item.day && (
              <div className="p-2">
                {getManipsInDay(item.date).map((manip, index) => (
                  <div
                    key={index}
                    className="absolute p-1"
                    style={{
                      top: `25%`,
                      left: `${(100 / maxIndex) * (index % maxIndex)}%`,
                      height: `75%`,
                      width: `${100 / maxIndex}%`,
                    }}
                  >
                    <button
                      className="h-full w-full rounded-full border border-black"
                      style={{
                        backgroundColor:
                          manip.userColor ?? "rgb(59 130 246 / 1)",
                      }}
                      onClick={() => handleManipClick(manip)}
                    ></button>
                  </div>
                ))}
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  );
};

export default MonthView;
