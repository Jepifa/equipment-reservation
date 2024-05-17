/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

/**
 * TimeNavBar component for displaying navigation controls for time.
 * @param {Object} props - Component props.
 * @param {string} props.date - Date to display.
 * @param {Function} props.onNextClick - Function to handle click event for navigating to the next time period.
 * @param {Function} props.onPrevClick - Function to handle click event for navigating to the previous time period.
 * @returns {JSX.Element} - TimeNavBar component.
 */
const TimeNavBar = ({ date, onNextClick, onPrevClick }) => {
  return (
    <div className="flex justify-between items-center mb-4">
      <div></div>
      <button
        className="text-blue-500 hover:text-blue-700 text-3xl px-4"
        onClick={onPrevClick}
      >
        &#8249;
      </button>
      <h2 className="text-lg font-semibold">{date}</h2>
      <button
        className="text-blue-500 hover:text-blue-700 text-3xl px-4"
        onClick={onNextClick}
      >
        &#8250;
      </button>
      <div></div>
    </div>
  );
};

export default TimeNavBar;
