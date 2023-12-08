// Function to fetch data from the server
async function fetchData() {
  try {
    const response = await fetch('../php/scripts/familyMembersAppointments/FamilyMemberAppointment.php');
    if (!response.ok) {
      throw new Error('Ophalen van afspraken is fout gegaan');
    }
    const data = await response.json();
    if(response) displayCards(data);
    else {
      alert('Geen afspraken gevonden');
      location.href = 'dashboard.html' 
    }
  } catch (error) {
    alert('Error:', error);
  }
}

// Function to create a card element
function createCard(appointment) {
  const card = document.createElement('div');
  card.classList.add('card');

  // Create elements for appointment details
  const elements = [
    createElement('h3', `Titel: ${appointment.Title}`),
    createElement('p', `Familie leden: ${appointment.FamilyMembers.map(member => member.Name).join(', ')}`),
    createElement('p', `Beschrijving: ${appointment.Description}`),
    createElement('p', `Datum: ${appointment.Date}`),
    createButton('Delete', appointment.AppointmentID),
    createButton('Assign', appointment.AppointmentID)
  ];

  elements.forEach(el => card.appendChild(el));
  return card;
}

// Function to retrieve session data
function getSessionData() {
  return fetch('../php/scripts/authentication/getSession.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Ophalen van de sessie is fout gegaan');
      }
      return response.json();
    })
    .then(data => data.FamilyMemberId)
}

// Function to create a button element
function createButton(action, appointmentID) {
  const button = document.createElement('button');
  button.textContent = action === 'Delete' ? 'Afmelden' : 'Aanmelden';
  button.classList.add(action.toLowerCase()); 
  button.addEventListener('click', () => {
    if (action === 'Delete') {
      getSessionData()
        .then(familyMemberId => {
          if (familyMemberId !== null) {
            fetch('../php/scripts/familyMembersAppointments/FamilyMemberAppointment.php', {
              method: 'DELETE',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({ familyMemberId, appointmentID })
            })
            .then(() => {
              fetchData(); // Reload data after deletion
            })
          } else {
            alert('FamilyMemberId niet in de sessie gevonden, log alsjeblieft opnieuw in.')
          }
        });
    } else if (action === 'Assign') {
      getSessionData()
        .then(familyMemberId => {
          if (familyMemberId !== null) {
            fetch('../php/scripts/familyMembersAppointments/FamilyMemberAppointment.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({ familyMemberId, appointmentID })
            })
            .then(() => {
              fetchData() // Reload data after assignment
            })
          } else {
            alert('FamilyMemberId niet in de sessie gevonden, log alsjeblieft opnieuw in.')
          }
        });
    }
  });

    getSessionData().then(familyMemberId => {
      if (familyMemberId !== null) {
        // Check if the appointment exists for the user
        fetch(`../php/scripts/appointments/Appointment.php?familyMemberID=${familyMemberId}&appointmentID=${appointmentID}`)
          .then(response => response.json())
          .then(data => {
            if (data.hasAppointment && action === 'Assign') {
              button.disabled = true; 
              button.classList.add('disabled');
            }
            if (!data.hasAppointment && action === 'Delete') {
              button.disabled = true; 
              button.classList.add('disabled');
            }
          })
      } else {
        alert('FamilyMemberId niet in de sessie gevonden, log alsjeblieft opnieuw in.')
      }
    });
  return button;
}

// Function to create a DOM(Document Object Model) element with given tag and content
function createElement(tag, content) {
  const element = document.createElement(tag);
  element.textContent = content;
  return element;
}

// Function to display cards with retrieved data
function displayCards(data) {
  const cardContainer = document.getElementById('cardContainer');
  cardContainer.innerHTML = '';

  const groupedAppointments = groupAppointments(data);

  for (const appointment of Object.values(groupedAppointments)) {
    const card = createCard(appointment);
    cardContainer.appendChild(card);
  }
}

// Group appointments by their unique ID
function groupAppointments(data) {
  return data.reduce((grouped, appointment) => {
    const appointmentID = appointment.AppointmentID;
    if (!grouped[appointmentID]) {
      grouped[appointmentID] = {
        Title: appointment.Title,
        Date: appointment.Date,
        Description: appointment.Description,
        FamilyMembers: [],
        AppointmentID: appointmentID
      };
    }
    grouped[appointmentID].FamilyMembers.push({
      FamilyMemberID: appointment.FamilyMemberID,
      Name: appointment.Name,
    });
    return grouped;
  }, {});
}

// Fetch initial data on page load
fetchData();
