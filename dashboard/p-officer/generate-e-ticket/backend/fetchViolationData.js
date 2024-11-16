function fetchViolationData() {
  const violationId = document.getElementById("violation_id").value;

  if (violationId) {
    // Send an AJAX request to fetch violation data from the backend
    fetch(`backend/get-violation-data.php?violation_id=${violationId}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Populate the fields with fetched data
          document.getElementById("violation_name").value = data.violation_name;
          document.getElementById("price").value = data.price;
        } else {
          alert("Violation data not found!");
        }
      })
      .catch((error) => console.error("Error fetching violation data:", error));
  } else {
    alert("Please enter a violation ID.");
  }
}
