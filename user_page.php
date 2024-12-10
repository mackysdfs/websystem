<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>NEMSU Navigation System</title>
   <link rel="stylesheet" href="css/user.css">
   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQ_UHiw2eut315x8s9zT5ZQy8vn_posiQ"></script>
</head>
<body>
  

<!-- Main Content -->
<div class="main-content">
   <!-- Left Panel (Fish Pond and Rooms) -->
   <div class="left-panel">
      <h2>Search Locations</h2>
      <input
         type="text"
         id="locationSearch"
         placeholder="Search for locations..."
         oninput="showSuggestions()"
      />
      <div id="suggestions" class="suggestions-list"></div>
      <p><a href="logout.php" class="btn">logout</a></p>
      <p><a href="user_transaction.php" class="btn">transactionForm</a></p>
   </div>

   <!-- Right Panel (Custom Google Map) -->
   <div class="map-display" id="map-display">
      <h2>MAP DISPLAY</h2>
      <div id="map" style="width: 100%; height: 450px;"></div>
   </div>
</div>

   <script>
      function filterLocations() {
    const searchInput = document.getElementById("locationSearch").value.toLowerCase();
    const buttons = document.querySelectorAll("#locationButtons button");
    const headers = document.querySelectorAll("#locationButtons h3");

    // Filter buttons based on the search input
    buttons.forEach((button) => {
        if (button.textContent.toLowerCase().includes(searchInput)) {
            button.style.display = "block";
        } else {
            button.style.display = "none";
        }
    });

    // Hide headers if no corresponding buttons are visible
    headers.forEach((header) => {
        const siblingButtons = [...header.nextElementSibling?.children || []];
        const isVisible = siblingButtons.some((btn) => btn.style.display === "block");
        header.style.display = isVisible ? "block" : "none";
    });
}
// Initialize Google Map
let map;
let activeMarker = null; // To keep track of the currently active marker

// Location data with coordinates and labels
const locations = {
    pond1: { lat: 8.626004, lng: 126.089082, label: "Pond 1" },
    pond2: { lat: 8.625572, lng: 126.088821, label: "Pond 2" },
    pond3: { lat: 8.625665, lng: 126.089295, label: "Pond 3" },
    pond4: { lat: 8.625429, lng: 126.089050, label: "Pond 4" },
    Building1: { lat: 8.626273, lng: 126.088727, label: "Building 1" },
    Building2: { lat: 8.626190, lng: 126.088505, label: "Building 2" },
    Building3: { lat: 8.625888, lng: 126.088482, label: "Building 3" },
    Building4: { lat: 8.624954, lng: 126.089798, label: "Building 4" },
};

// Extract locations into an array for search suggestions
const locationList = Object.keys(locations).map(key => ({
    id: key,
    name: locations[key].label,
}));

// Function to show filtered suggestions
function showSuggestions() {
    const searchInput = document.getElementById("locationSearch").value.toLowerCase();
    const suggestionsDiv = document.getElementById("suggestions");

    // Clear existing suggestions
    suggestionsDiv.innerHTML = "";

    if (searchInput.trim() === "") {
        suggestionsDiv.style.display = "none";
        return;
    }

    // Filter locations based on the search input
    const filteredLocations = locationList.filter(location =>
        location.name.toLowerCase().includes(searchInput)
    );

    if (filteredLocations.length === 0) {
        suggestionsDiv.style.display = "none";
        return;
    }

    // Create suggestion items
    filteredLocations.forEach(location => {
        const suggestionItem = document.createElement("div");
        suggestionItem.textContent = location.name;
        suggestionItem.className = "suggestion-item";
        suggestionItem.onclick = () => moveToLocation(location.id);
        suggestionsDiv.appendChild(suggestionItem);
    });

    suggestionsDiv.style.display = "block";
}

// Function to move to a specific location on the map
function moveToLocation(locationId) {
    const location = locations[locationId];
    if (!location) return;

    // Center map on the selected location
    map.setCenter({ lat: location.lat, lng: location.lng });
    map.setZoom(18);

    // Update or create the active marker
    if (activeMarker) {
        activeMarker.setMap(null); // Remove previous marker
    }
    activeMarker = new google.maps.Marker({
        position: { lat: location.lat, lng: location.lng },
        map,
        title: location.label,
    });

    console.log("Navigated to:", location.label);
}

// Initialize the Google Map
function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 8.626004, lng: 126.089082 },
        zoom: 16,
    });
}


      function initMap() {
         map = new google.maps.Map(document.getElementById('map'), {
            zoom: 16,
            center: { lat: 8.62498, lng: 126.08725 },
            mapTypeId: 'roadmap',
         });

         // Add Roads using Polylines
         const roads = [
            {
               path: [
                  { lat: 8.6245, lng: 126.0865 },
                  { lat: 8.6255, lng: 126.0875 },
               ],
               color: '#FF0000',
            },
            {
               path: [
                  { lat: 8.6250, lng: 126.0870 },
                  { lat: 8.6260, lng: 126.0878 },
               ],
               color: '#0000FF',
            },
         ];

         roads.forEach((road) => {
            new google.maps.Polyline({
               path: road.path,
               geodesic: true,
               strokeColor: road.color,
               strokeOpacity: 1.0,
               strokeWeight: 2,
               map: map,
            });
         });
      }

      // Function to move to a specific location and show only one marker
      function moveToLocation(locationKey) {
         const location = locations[locationKey];
         if (!location) return;

         // Remove the previous marker, if any
         if (activeMarker) {
            activeMarker.setMap(null);
         }

         // Add a new marker
         activeMarker = new google.maps.Marker({
            position: { lat: location.lat, lng: location.lng },
            map: map,
            label: location.label,
         });

         // Center and zoom the map on the new location
         map.panTo({ lat: location.lat, lng: location.lng });
         map.setZoom(19);
      }

      window.onload = initMap;
   </script>
</body>
</html>
