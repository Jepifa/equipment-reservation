/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { Card, CardContent, Grid, Typography } from "@mui/material";

/**
 * ManipDetails component to display details of a manipulation.
 * @param {Object} props - The props object.
 * @param {Object} props.manip - The manipulation object.
 * @returns {JSX.Element} - The ManipDetails component.
 */
const ManipDetails = ({ manip }) => {
  return (
    <Grid container spacing={2}>
      {/* Pro */}
      <Grid
        item
        xs={12}
        sm={6}
        style={{ display: "flex", flexDirection: "column" }}
      >
        <Card variant="outlined" style={{ flex: 1 }}>
          <CardContent>
            <Typography variant="h6" gutterBottom>
              Pro:
            </Typography>
            <Typography variant="body1">{manip.userName}</Typography>
          </CardContent>
        </Card>
      </Grid>
      {/* Team */}
      <Grid
        item
        xs={12}
        sm={6}
        style={{ display: "flex", flexDirection: "column" }}
      >
        <Card variant="outlined" style={{ flex: 1 }}>
          <CardContent>
            <Typography variant="h6" gutterBottom>
              Team:
            </Typography>
            <ul style={{ margin: 0, paddingInlineStart: "1rem" }}>
              {manip.team.map((user, index) => (
                <li key={`user-${index}`}>
                  <Typography variant="body1">{user.name}</Typography>
                </li>
              ))}
            </ul>
          </CardContent>
        </Card>
      </Grid>
      {/* Location */}
      <Grid item xs={12}>
        <Card variant="outlined">
          <CardContent>
            <Typography variant="h6" gutterBottom>
              Location:
            </Typography>
            <Typography variant="body1">
              {manip.locationName}, {manip.siteName}
            </Typography>
          </CardContent>
        </Card>
      </Grid>
      {/* Equipment */}
      <Grid item xs={12}>
        <Card variant="outlined">
          <CardContent>
            <Typography variant="h6" gutterBottom>
              Equipment:
            </Typography>
            <ul>
              {manip.equipments.map((equipment, index) => (
                <li key={index}>
                  {equipment.name} - {equipment.equipmentGroupName} -{" "}
                  {equipment.categoryName}{" "}
                  {!equipment.operational ? (
                    <>
                      {" - "}
                      <span style={{ color: "red" }}>
                        This equipment is currently unavailable.
                      </span>
                    </>
                  ) : null}
                </li>
              ))}
            </ul>
          </CardContent>
        </Card>
      </Grid>
      {/* Start Date */}
      <Grid item xs={12} sm={6}>
        <Card variant="outlined">
          <CardContent>
            <Typography variant="h6" gutterBottom>
              Start Date:
            </Typography>
            <Typography variant="body1">{manip.beginDate}</Typography>
          </CardContent>
        </Card>
      </Grid>
      {/* End Date */}
      <Grid item xs={12} sm={6}>
        <Card variant="outlined">
          <CardContent>
            <Typography variant="h6" gutterBottom>
              End Date:
            </Typography>
            <Typography variant="body1">{manip.endDate}</Typography>
          </CardContent>
        </Card>
      </Grid>
    </Grid>
  );
};

export default ManipDetails;
