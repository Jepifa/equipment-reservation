/**
 * Author: Jean-Pierre Faucon
 * Version: 1.0
 */

import { useEffect, useState } from "react";

import { useGetEquipmentsQuery } from "../../equipment/equipment/equipmentSlice.service";
import { useGetUsersQuery } from "../../users/usersSlice.service";

import Filter from "./Filter";

/**
 * Filters component for filtering manipulations/events by users, equipment, and team members.
 * @param {Object} props - Component props.
 * @param {Array} props.manips - Array of manipulations/events to filter.
 * @param {Function} props.setFilteredManips - Function to set the filtered manipulations/events.
 * @param {Function} props.setErrorMessage - Function to set error message.
 * @returns {JSX.Element} - Filters component.
 */
const Filters = ({ manips, setFilteredManips, setErrorMessage }) => {
  const { data: users = [], isError: isGetUsersError } = useGetUsersQuery();
  const { data: equipments = [], isError: isGetEquipmentsError } =
    useGetEquipmentsQuery();

  const [selectedEquipments, setSelectedEquipments] = useState([]);
  const [selectedTeamMembers, setSelectedTeamMembers] = useState([]);
  const [selectedUsers, setSelectedUsers] = useState([]);

  useEffect(() => {
    if (isGetUsersError) {
      setErrorMessage(
        "An error occurred while loading users. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
    if (isGetEquipmentsError) {
      setErrorMessage(
        "An error occurred while loading equipment. Please try again later. If the issue persists, you can try reloading the page or contact technical support for assistance."
      );
    }
  });

  useEffect(() => {
    let filtered = manips;

    if (selectedUsers.length > 0) {
      const selectedUserIds = selectedUsers.map((user) => user.id);
      filtered = filtered.filter((item) =>
        selectedUserIds.includes(item.userId)
      );
    }

    if (selectedEquipments.length > 0) {
      const selectedEquipmentIds = selectedEquipments.map(
        (equipment) => equipment.id
      );
      filtered = filtered.filter((item) =>
        item.equipmentIds.some((id) => selectedEquipmentIds.includes(id))
      );
    }

    if (selectedTeamMembers.length > 0) {
      const selectedTeamMembersIds = selectedTeamMembers.map((user) => user.id);
      filtered = filtered.filter(
        (item) =>
          item.teamIds.some((id) => selectedTeamMembersIds.includes(id)) ||
          selectedTeamMembersIds.includes(item.userId)
      );
    }

    setFilteredManips(filtered);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [manips, selectedUsers, selectedEquipments, selectedTeamMembers]);

  /**
   * Handles the change event of a filter.
   * @param {Object} event - Change event object.
   * @param {Function} setSelectedItems - Function to set the selected items.
   */
  const handleFilterChange = (event, setSelectedItems) => {
    const eventSelectedItems = event.target.value || [];
    setSelectedItems(eventSelectedItems);
  };

  return (
    <>
      {/* User Filter */}
      <Filter
        name="userFilter"
        label="User Filter"
        items={users}
        selectedItems={selectedUsers}
        handleFilterChange={(event) =>
          handleFilterChange(event, setSelectedUsers)
        }
        sx={{ mr: 2, width: 300 }}
      />
      {/* Equipment Filter */}
      <Filter
        name="equipmentFilter"
        label="Equipment Filter"
        items={equipments}
        selectedItems={selectedEquipments}
        handleFilterChange={(event) =>
          handleFilterChange(event, setSelectedEquipments)
        }
        sx={{ mr: 2, width: 300 }}
      />
      {/* Team Filter */}
      <Filter
        name="teamFilter"
        label="Team Filter"
        items={users}
        selectedItems={selectedTeamMembers}
        handleFilterChange={(event) =>
          handleFilterChange(event, setSelectedTeamMembers)
        }
        sx={{ width: 300 }}
      />
    </>
  );
};

export default Filters;
