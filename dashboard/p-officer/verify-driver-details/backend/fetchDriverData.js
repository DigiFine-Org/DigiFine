function fetchDriverData() {
  const id = document.getElementById("id").value;

  if (id) {
    // Send an AJAX request to fetch driver data from the backend
    fetch(`../generate-e-ticket/backend/get-driver-data.php?id=${id}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Populate the fields with fetched data
          document.getElementById("full_name").value = data.full_name;
          document.getElementById("d_address").value = data.d_address;
          document.getElementById("license_valid_from").value =
            data.license_valid_from;
          document.getElementById("license_valid_to").value =
            data.license_valid_to;
          document.getElementById("competent_categories").innerHTML =
            data.competent_categories;
        } else {
          alert("Driver data not found!");
        }
      })
      .catch((error) => console.error("Error fetching driver data:", error));
  } else {
    alert("Please enter a license number.");
  }
}