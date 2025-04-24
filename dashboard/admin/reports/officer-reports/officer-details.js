function fetchOfficerInfo() {
  const officerId = document.getElementById("officerId").value;
  const officerInfoContainer = document.getElementById("officerInfoContainer");
  const officerDetails = document.getElementById("officerDetails");

  if (!officerId) {
    alert("Please enter an Officer ID");
    return;
  }

  // Show loading indicator
  officerDetails.innerHTML = "<p>Loading...</p>";
  officerInfoContainer.style.display = "block";

  // Fetch officer data
  fetch(`get-officer-details.php?officer_id=${officerId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        officerDetails.innerHTML = `<p class="description" style="color: red">${data.error}</p>`;
        return;
      }

      const officer = data.data;
      officerDetails.innerHTML = `
<div class="officer-inline-info" style="display: flex; gap: 4rem;">
  <p class="description" style="font-size: 1.3rem;"><strong>Name:</strong> ${
    officer.fname
  } ${officer.lname}</p>
  <p class="description" style="font-size: 1.3rem;"><strong>Is OIC:</strong> ${
    officer.is_oic == 1 ? "Yes" : "No"
  }</p>
</div>



    `;
    })
    .catch((error) => {
      console.error("Error:", error);
      officerDetails.innerHTML =
        '<p class="description" style="color: red">Error fetching officer information</p>';
    });
}
