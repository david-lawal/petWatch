document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("liveSearchInput");
    const resultsContainer = document.getElementById("searchResults");
    const isLoggedIn = document.getElementById("isLoggedIn")?.value === "1";

    if (!searchInput || !resultsContainer) {
        return;
    }

    let timeout = null;

    searchInput.addEventListener("input", function () {
        const term = searchInput.value.trim();

        clearTimeout(timeout);

        timeout = setTimeout(() => {
            if (term === "") {
                resultsContainer.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <strong>Start typing to search for pets.</strong>
                        </div>
                    </div>
                `;
                return;
            }

            fetch(`/clientserver/liveSearch.php?term=${encodeURIComponent(term)}`)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = "";

                    if (data.length === 0) {
                        resultsContainer.innerHTML = `
                            <div class="col-12">
                                <div class="alert alert-warning text-center">
                                    <strong>No pets found matching "${term}".</strong>
                                </div>
                            </div>
                        `;
                        return;
                    }

                    data.forEach(pet => {
                        const badgeClass = pet.status === "lost" ? "bg-danger" : "bg-success";

                        let actionButtons = `
                            <a href="viewSighting.php?pet_id=${pet.id}"
                               class="btn btn-primary-green btn-sm rounded-pill shadow-sm">
                                View Sightings
                            </a>
                        `;

                        if (isLoggedIn) {
                            actionButtons = `
                                <a href="leaveSighting.php?pet_id=${pet.id}"
                                   class="btn btn-primary-green btn-sm rounded-pill shadow-sm me-1">
                                    Leave Sighting
                                </a>
                                <a href="viewSighting.php?pet_id=${pet.id}"
                                   class="btn btn-primary-green btn-sm rounded-pill shadow-sm">
                                    View Sightings
                                </a>
                            `;
                        }

                        const card = document.createElement("div");
                        card.className = "col-md-4 mb-4";

                        card.innerHTML = `
                            <div class="card-body text-center border rounded shadow-sm p-3 bg-light">
                                <h5 class="card-title fw-bold">${pet.name}</h5>
                                <p class="card-text mb-1">
                                    <strong>Species:</strong> ${pet.species}<br>
                                    <strong>Status:</strong>
                                    <span class="badge ${badgeClass}">
                                        ${pet.status}
                                    </span><br>
                                    <small class="text-muted">
                                        Reported: ${pet.date_reported ?? ""}
                                    </small>
                                </p>
                                <p class="card-text small mt-2">
                                    ${pet.description ? pet.description.replace(/\n/g, "<br>") : ""}
                                </p>
                                <div class="mt-3">
                                    ${actionButtons}
                                </div>
                            </div>
                        `;

                        resultsContainer.appendChild(card);
                    });
                })
                .catch(error => {
                    console.error("Search error:", error);
                    resultsContainer.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-danger text-center">
                                <strong>Failed to load search results.</strong>
                            </div>
                        </div>
                    `;
                });
        }, 300);
    });
});