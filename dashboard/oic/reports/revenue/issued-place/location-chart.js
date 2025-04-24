window.fetchIssuedPlaceFineData = function () {
  const timePeriod = document.getElementById("timePeriod").value;

  fetch(`issued-place/location-get-fines.php?time_period=${timePeriod}`)
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched location data:", data);
      if (data.error) {
        s;
        alert(data.error);
      } else {
        const top30Data = data.slice(0, 30); // Use only the top 30
        updateIssuedPlaceChart(top30Data, timePeriod);
        updateFineSummary5(data, timePeriod);
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
};

function updateIssuedPlaceChart(data, period) {
  const ctx = document.getElementById("issuedPlaceChart").getContext("2d");

  if (window.issuedPlaceChart1) {
    window.issuedPlaceChart1.destroy();
  }

  const labels = data.map((item) => item.label);
  const counts = data.map((item) => item.count);

  window.issuedPlaceChart1 = new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: `Fines by police officer (${period})`,
          data: counts,
          backgroundColor: "rgba(54, 162, 235, 0.6)",
          borderColor: "rgba(54, 162, 235, 1)",
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "total fine amount (Rs.)",
          },
        },
        x: {
          title: {
            display: true,
            text: "Police Officer",
          },
        },
      },
      plugins: {
        title: {
          display: true,
          text: `Total Fines - Location (${period})`,
        },
        legend: {
          display: false,
        },
      },
    },
  });
}
// fetchIssuedPlaceFineData(); // on load display
