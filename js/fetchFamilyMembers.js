// Function to fetch family members from the server and create the select dropdown
function fetchFamilyMembers() {
  fetch('../php/scripts/familyMembers/getFamilyMembers.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Fetching family members failed');
      }
      return response.json();
    })
    .then(data => {
      const select = document.createElement('select');
      select.setAttribute('name', 'familyMembers[]');
      select.setAttribute('multiple', '');
      select.style.width = '100%';

      data.forEach(member => {
        const option = document.createElement('option');
        option.setAttribute('value', member.FamilyMemberID);
        option.textContent = member.Name;
        select.appendChild(option);
      });

      const label = document.createElement('label');
      label.setAttribute('for', 'familyMembers');
      label.textContent = 'Selecteer familie leden:';

      const container = document.getElementById('familyMembers');
      container.appendChild(label);
      container.appendChild(select);
    })
    .catch(error => {
      console.error('Error fetching family members:', error);
    });
}

// Call the fetchFamilyMembers function to populate the select dropdown
fetchFamilyMembers();
