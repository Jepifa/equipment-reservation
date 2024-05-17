/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

/**
 * ManipWeekView component for displaying manipulation/event details in the week view of the calendar.
 * @param {Object} props - Component props.
 * @param {Object} props.manip - Manipulation/event object to display.
 * @param {number} props.index - Index of the manipulation/event.
 * @param {Array} props.daysInWeek - Array of Date objects representing the days in the week view.
 * @param {Function} props.handleManipClick - Function to handle manipulation/event click.
 * @param {number} props.maxIndex - Maximum index of displayed manipulations/events.
 * @returns {JSX.Element} - ManipWeekView component.
 */
const ManipWeekView = ({
  manip,
  index,
  daysInWeek,
  handleManipClick,
  maxIndex,
}) => {
  // Half hour height for calendar week view
  const hhh = 3;
  const currentIndex = index % maxIndex;

  let beginDate = new Date(manip.beginDate);
  let endDate = new Date(manip.endDate);

  /**
   * Adjusts begin and end dates of the manipulation/event based on the current week view.
   */
  const checkBeginAndEndDate = () => {
    const firstDayOfWeek = daysInWeek[0];
    firstDayOfWeek.setHours(7, 0, 0);
    const lastDayOfWeek = daysInWeek[6];
    lastDayOfWeek.setHours(19, 0, 0);

    beginDate = new Date(Math.max(beginDate, firstDayOfWeek));
    endDate = new Date(Math.min(endDate, lastDayOfWeek));
  };

  /**
   * Checks if the begin and end dates of the manipulation/event are on the same day.
   * @returns {boolean} - True if the dates are on the same day, false otherwise.
   */
  const isSameDay = () => {
    return (
      beginDate.getFullYear() === endDate.getFullYear() &&
      beginDate.getMonth() === endDate.getMonth() &&
      beginDate.getDate() === endDate.getDate()
    );
  };

  /**
   * Retrieves the X coordinates for the manipulation/event based on the days in the week.
   * @returns {Array} - Array of X coordinates.
   */
  const getXCoordinates = () => {
    checkBeginAndEndDate();
    const xCoordinates = [];

    if (isSameDay()) {
      xCoordinates.push(beginDate.getDay() || 7);
    } else {
      for (let i = beginDate.getDay(); i <= (endDate.getDay() || 7); i++) {
        xCoordinates.push(i || 7);
      }
    }

    return xCoordinates;
  };

  /**
   * Calculates the number of half-hours from the beginning of the day to the start of the manipulation/event.
   * @returns {number} - Number of half-hours.
   */
  const manipBeginNbHalfHours = () => {
    const beginHour = beginDate.getHours();
    const beginMinute = beginDate.getMinutes();
    const nbHalfHours = Math.max((beginHour - 7) * 2, 0);
    return beginMinute === 0 ? nbHalfHours : nbHalfHours + 1;
  };

  /**
   * Calculates the number of half-hours duration of the manipulation/event.
   * @returns {Array} - Array containing the number of half-hours for each day.
   */
  const manipNbHalfHours = () => {
    if (isSameDay()) {
      const timeDifference = endDate.getTime() - beginDate.getTime();
      const nbHalfHours = timeDifference / (1000 * 1800); // 1800 seconds in half an hour
      return [nbHalfHours];
    } else {
      const beginNbHalfHours =
        (19 - beginDate.getHours()) * 2 -
        (beginDate.getMinutes() === 0 ? 0 : 1);
      const endNbHalfHours =
        (endDate.getHours() - 7) * 2 + (endDate.getMinutes() === 0 ? 0 : 1);
      return [beginNbHalfHours, endNbHalfHours];
    }
  };

  const manipXCoordinates = getXCoordinates();
  const manipYCoordinate = manipBeginNbHalfHours();

  const elementStyles = manipXCoordinates.map((xCoordinate, index) => ({
    top: index === 0 ? `${manipYCoordinate * hhh}rem` : "0",
    left: `${(100 / 8) * xCoordinate + (100 / (8 * maxIndex)) * currentIndex}%`,
    height:
      index === 0
        ? `${manipNbHalfHours()[0] * hhh}rem`
        : index === manipXCoordinates.length - 1
        ? `${manipNbHalfHours()[1] * hhh}rem`
        : `${24 * hhh}rem`,
    width: `${12.5 / maxIndex}%`,
  }));

  return (
    <>
      {elementStyles.map((elementStyle, index) => (
        <div key={index} className="absolute px-1" style={elementStyle}>
          <button
            className="block h-full w-full rounded-full border border-black "
            style={{
              backgroundColor: manip.userColor ?? "rgb(59 130 246 / 1)",
            }}
            onClick={() => handleManipClick(manip)}
          ></button>
        </div>
      ))}
    </>
  );
};

export default ManipWeekView;
