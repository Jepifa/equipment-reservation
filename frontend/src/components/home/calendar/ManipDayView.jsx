/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import ManipDetails from "../../manip/ManipDetails";

/**
 * ManipDayView component for displaying manipulation/event details in the day view of the calendar.
 * @param {Object} props - Component props.
 * @param {Object} props.manip - Manipulation/event object to display.
 * @param {number} props.index - Index of the manipulation/event.
 * @param {Date} props.day - Date of the day view.
 * @param {Function} props.handleManipClick - Function to handle manipulation/event click.
 * @param {number} props.maxIndex - Maximum index of displayed manipulations/events.
 * @returns {JSX.Element} - ManipDayView component.
 */
const ManipDayView = ({ manip, index, day, handleManipClick, maxIndex }) => {
  // Half hour height for calendar day view
  const hhh = 3;
  const currentIndex = index % maxIndex;

  let beginDate = new Date(manip.beginDate);
  let endDate = new Date(manip.endDate);

  /**
   * Adjusts begin and end dates of the manipulation/event based on the current day view.
   */
  const checkBeginAndEndDate = () => {
    const beginCurrentDate = new Date(day);
    beginCurrentDate.setHours(7, 0, 0);
    const endCurrentDate = new Date(day);
    endCurrentDate.setHours(19, 0, 0);

    beginDate = new Date(Math.max(beginDate, beginCurrentDate));
    endDate = new Date(Math.min(endDate, endCurrentDate));
  };

  /**
   * Calculates the number of half-hours from the beginning of the day to the start of the manipulation/event.
   * @returns {number} - Number of half-hours.
   */
  const manipBeginNbHalfHours = () => {
    checkBeginAndEndDate();
    const beginHour = beginDate.getHours();
    const beginMinute = beginDate.getMinutes();
    const nbHalfHours = Math.max((beginHour - 7) * 2, 0);
    return beginMinute === 0 ? nbHalfHours : nbHalfHours + 1;
  };

  /**
   * Calculates the number of half-hours duration of the manipulation/event.
   * @returns {number} - Number of half-hours.
   */
  const manipNbHalfHours = () => {
    const timeDifference = endDate.getTime() - beginDate.getTime();
    const nbHalfHours = timeDifference / (1000 * 1800); // 1800 seconds in half an hour
    return nbHalfHours;
  };

  const manipYCoordinate = manipBeginNbHalfHours();

  const elementStyle = {
    top: `${manipYCoordinate * hhh}rem`,
    left: `${100 / 8 + (87.5 / maxIndex) * currentIndex}%`,
    height: `${manipNbHalfHours() * hhh}rem`,
    width: `${87.5 / maxIndex}%`,
  };

  return (
    <>
      <div key={index} className="absolute px-1" style={elementStyle}>
        <div
          className="h-full w-full text-white rounded-xl border border-black flex items-center cursor-pointer"
          style={{
            backgroundColor: manip.userColor ?? "rgb(59 130 246 / 1)",
          }}
          onClick={() => handleManipClick(manip)}
        >
          <div className="p-4 h-full overflow-y-auto scrollbar-hide ">
            <ManipDetails manip={manip} />
          </div>
        </div>
      </div>
    </>
  );
};

export default ManipDayView;
