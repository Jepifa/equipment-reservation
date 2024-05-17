/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Skeleton, TableCell, TableRow } from "@mui/material";

/**
 * Component for rendering skeleton loading rows in a table.
 * @param {number} rowsNum - The number of rows to render.
 * @param {number} colsNum - The number of columns per row.
 * @returns {JSX.Element[]} - An array of JSX elements representing the skeleton loading rows.
 */
const TableRowsLoader = ({ rowsNum, colsNum }) => {
  // Create an array of rows with skeleton loading cells
  return [...Array(rowsNum)].map((row, rowIndex) => (
    <TableRow key={rowIndex}>
      {[...Array(colsNum)].map((col, colIndex) => (
        <TableCell key={colIndex} component="th" scope="row">
          <Skeleton animation="wave" variant="text" />
        </TableCell>
      ))}
    </TableRow>
  ));
};

export default TableRowsLoader;
