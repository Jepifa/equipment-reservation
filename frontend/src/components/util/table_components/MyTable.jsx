/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import {
  Paper,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Typography,
} from "@mui/material";

import TableRowsLoader from "./TableRowsLoader";

/**
 * MyTable component for rendering a custom styled table.
 * @param {Object} props - The component props.
 * @param {string[]} props.titles - An array of table column titles.
 * @param {JSX.Element[]} props.rows - An array of JSX elements representing table rows.
 * @param {boolean} props.isLoadingOrFetching - Boolean flag indicating whether data is loading or fetching.
 * @param {number} props.rowsNum - The number of rows in the table.
 * @returns {JSX.Element} - The rendered component.
 */
const MyTable = ({ titles, rows, isLoadingOrFetching, rowsNum }) => {
  return (
    <TableContainer component={Paper}>
      <Table className="min-w-full">
        <TableHead>
          <TableRow>
            {titles.map((title, index) => (
              <TableCell key={index}>
                <Typography variant="h6">{title}</Typography>
              </TableCell>
            ))}
          </TableRow>
        </TableHead>
        <TableBody>
          {isLoadingOrFetching ? (
            <TableRowsLoader rowsNum={rowsNum} colsNum={titles.length + 1} />
          ) : (
            rows
          )}
        </TableBody>
      </Table>
    </TableContainer>
  );
};

export default MyTable;
