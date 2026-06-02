document.addEventListener("DOMContentLoaded", function () {
    const mapElement = document.getElementById("map");
    const sightingsList = document.getElementById("sightingsList");
    const form = document.getElementById("addSightingForm");
    const formMessage = document.getElementById("formMessage");

    if (!mapElement) {
        return;
    }

    const map = L.map("map").setView([53.4808, -2.2426], 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors"
    }).addTo(map);

    let markerLayer = L.layerGroup().addTo(map);

    function loadSightings() {
        markerLayer.clearLayers();
        sightingsList.innerHTML = "<p>Loading sightings...</p>";

        fetch("/clientserver/getSightings.php")
            .then(response => response.json())
            .then(data => {
                sightingsList.innerHTML = "";

                if (data.length === 0) {
                    sightingsList.innerHTML = "<p>No sightings found.</p>";
                    return;
                }

                const markers = [];

                data.forEach(sighting => {
                    const lat = parseFloat(sighting.latitude);
                    const lng = parseFloat(sighting.longitude);

                    if (isNaN(lat) || isNaN(lng)) {
                        return;
                    }

                    const popupText = `
    					<strong>${sighting.pet_name}</strong><br>
    					${sighting.comment}<br>
    					<small>${sighting.timestamp}</small>
					`;

                	const greenIcon = new L.Icon({
    					iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png",
    					shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png",
    					iconSize: [25, 41],
    					iconAnchor: [12, 41],
    					popupAnchor: [1, -34],
    					shadowSize: [41, 41]
					});

const marker = L.marker([lat, lng], { icon: greenIcon })
    .bindPopup(popupText);
                
                    

                    markerLayer.addLayer(marker);
                    markers.push(marker);

                    const item = document.createElement("div");
                    item.classList.add("mb-3", "p-2", "border", "rounded", "bg-white");
                    item.style.cursor = "pointer";
                    item.innerHTML = `
                        <strong>${sighting.pet_name}</strong><br>
                        <small>${sighting.comment}</small>
                    `;

                    item.addEventListener("click", function () {
                        map.setView([lat, lng], 15);
                        marker.openPopup();
                    });

                    sightingsList.appendChild(item);
                });

                if (markers.length > 0) {
                    const group = L.featureGroup(markers);
                    map.fitBounds(group.getBounds().pad(0.2));
                }
            })
            .catch(error => {
                console.error("Error loading sightings:", error);
                sightingsList.innerHTML = "<p>Failed to load sightings.</p>";
            });
    }

    loadSightings();

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                L.marker([userLat, userLng])
                    .addTo(map)
                    .bindPopup("You are here");
            },
            function () {
                console.log("Geolocation permission denied or unavailable.");
            }
        );
    }

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(form);

            for (let pair of formData.entries()) {
                console.log(pair[0] + ": " + pair[1]);
            }

            fetch("/clientserver/addSightings.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    formMessage.textContent = data.message;
					formMessage.className = data.success ? "text-success" : "text-danger";

                    if (data.success) {
                        form.reset();
                        loadSightings();
                    }
                })
                .catch(error => {
                    console.error("Error adding sighting:", error);
                    formMessage.textContent = "Failed to add sighting.";
                });
        });
    }
});