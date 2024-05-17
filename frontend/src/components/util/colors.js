/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import hexRgb from "hex-rgb";
import rgbHex from "rgb-hex";

/**
 * Function to convert an RGBA object to CSS rgba string.
 * @param {Object} rgba - The RGBA object containing red, green, blue, and alpha values.
 * @returns {string} - The CSS rgba string.
 */
export const getRgbaCss = (rgba) => {
  const { r, g, b, a } = rgba;
  return `rgba(${r}, ${g}, ${b}, ${a})`;
};

/**
 * Function to convert an RGBA object to a hexadecimal color string.
 * @param {Object} rgba - The RGBA object containing red, green, blue, and alpha values.
 * @returns {string} - The hexadecimal color string.
 */
export const getHexColor = (rgba) => {
  const string = Object.values(rgba).join(",");
  const rgbaColor = "rgba(" + string + ")";
  return "#" + rgbHex(rgbaColor);
};

/**
 * Function to convert a hexadecimal color string to an RGBA object.
 * @param {string} hex - The hexadecimal color string.
 * @returns {Object} - The RGBA object containing red, green, blue, and alpha values.
 */
export const getRgbaObject = (hex) => {
  const { red: r, green: g, blue: b, alpha: a } = hexRgb(hex);
  return { r, g, b, a };
};
