/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect, useState } from "react";

import { RgbaColorPicker } from "react-colorful";

import { useChangeColorMutation } from "../users/usersSlice.service";
import { useGetManipsQuery } from "../manip/manipsSlice.service";

import { getHexColor, getRgbaCss, getRgbaObject } from "./colors";
import useAuthContext from "../../context/AuthContext";

/**
 * Component for rendering a color picker.
 * @returns {JSX.Element} - The rendered component.
 */
const ColorPicker = ({ setErrorMessage }) => {
  const { refetch } = useGetManipsQuery();
  const { user } = useAuthContext();

  const [changeColor] = useChangeColorMutation();

  const defaultColor = { r: 59, g: 130, b: 246, a: 1 };

  const [changing, setChanging] = useState(false);
  const [selectedColor, setSelectedColor] = useState({ ...defaultColor });
  const [colorInput, setColorInput] = useState(getHexColor(selectedColor));
  const [showColorPicker, setShowColorPicker] = useState(false);

  useEffect(() => {
    const color = user.color ? user.color : "#3b82f6ff";
    try {
      setSelectedColor(getRgbaObject(color));
      setColorInput(color);
    } catch (error) {
      handleColorChanged();
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [user]);

  /**
   * Function to handle color change.
   * @param {Object} color - The new color object.
   */
  const handleColorChange = (color) => {
    setSelectedColor(color);
    setColorInput(getHexColor(color));
  };

  /**
   * Function to handle color input change.
   * @param {Object} event - The input change event.
   */
  const handleOnColorInputChange = ({ target }) => {
    try {
      const { value } = target;
      setColorInput(value);
      const rgbaColor = getRgbaObject(value);
      setSelectedColor(rgbaColor);
    } catch (error) {
      /* empty */
    }
  };

  /**
   * Function to hide the color picker.
   */
  const hideColorPicker = () => {
    handleColorChanged();
    setShowColorPicker(false);
  };

  /**
   * Function to handle color change.
   */
  const handleColorChanged = async () => {
    try {
      const colorInputWithHash = encodeURIComponent(colorInput);
      await changeColor({ id: user.id, color: colorInputWithHash }).unwrap();
      refetch();
    } catch (error) {
      setErrorMessage(
        "An error occurred while changing color. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  };

  return (
    <div className="relative" onBlur={!changing ? hideColorPicker : undefined}>
      <button
        className="w-8 h-8 rounded-full relative"
        onClick={() =>
          showColorPicker ? hideColorPicker() : setShowColorPicker(true)
        }
      >
        <div
          className="absolute rounded-full border-2 border-gray-300 -inset-0 z-10"
          style={{ background: getRgbaCss(selectedColor) }}
        ></div>
      </button>
      {showColorPicker && (
        <div
          className="absolute top-full right-0 bg-white z-10 p-2 shadow-md rounded-xl shadow-black mt-3"
          onMouseDownCapture={() => setChanging(true)}
          onMouseUp={() => setChanging(false)}
          onMouseLeave={() => setChanging(false)}
        >
          <RgbaColorPicker color={selectedColor} onChange={handleColorChange} />
          <div className="w-full py-2">
            <input
              type="text"
              className=" border-neutral-400 border-2 rounded-lg outline-none text-black p-2 w-full text-center"
              value={colorInput}
              onChange={handleOnColorInputChange}
              onBlur={() => setColorInput(getHexColor(selectedColor))}
            />
          </div>
        </div>
      )}
    </div>
  );
};

export default ColorPicker;
