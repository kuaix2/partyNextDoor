// scripts.js

document.getElementById('hostEventButton').addEventListener('click', function() {
    // Show the event form when the "Host an Event" button is clicked
    document.getElementById('eventFormContainer').style.display = 'block';
  });
  
  document.getElementById('eventForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting immediately
  
    const eventName = document.getElementById('eventName').value;
    const eventDate = document.getElementById('eventDate').value;
    const eventDescription = document.getElementById('eventDescription').value;
  
    // Send the event data to the server
    fetch('publish_event.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        event_name: eventName,
        event_date: eventDate,
        event_description: eventDescription
      })
    })
    .then(response => response.text())
    .then(data => {
      if (data === 'Event added successfully') {
        // If successful, add the event to the list
        const eventList = document.getElementById('eventList');
        const listItem = document.createElement('li');
        listItem.textContent = `${eventName} - ${eventDate}: ${eventDescription}`;
        eventList.appendChild(listItem);
        
        // Clear the form
        document.getElementById('eventForm').reset();
        document.getElementById('eventFormContainer').style.display = 'none';
      } else {
        alert('Error adding event.');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred. Please try again.');
    });
  });
  