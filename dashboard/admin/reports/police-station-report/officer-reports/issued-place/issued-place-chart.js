window.fetchIssuedPlaceData = function () {
  const officerId = document.getElementById("officerId").value;
  const timePeriod = document.getElementById("timePeriod").value;

  fetch(
    `issued-place/get-fines.php?police_id=${officerId}&time_period=${timePeriod}`
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched location data:", data);
      if (data.error) {
        alert(data.error);
      } else {
        const top30Data = data.slice(0, 30); // Use only the top 30 locations for the chart
        updateIssuedPlacenChart(top30Data, timePeriod);
        updateIssuedPlaceSummary(data, timePeriod);
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
};

function updateIssuedPlacenChart(data, period) {
  const ctx = document.getElementById("issuedPlaceChart").getContext("2d");

  if (window.placeChart) {
    window.placeChart.destroy();
  }

  const labels = data.map((item) => item.label);
  const counts = data.map((item) => item.count);

  window.placeChart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: `Fines by Location (${period})`,
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
            text: "Number of Fines",
          },
        },
        x: {
          title: {
            display: true,
            text: "Location",
          },
        },
      },
      plugins: {
        title: {
          display: true,
          text: `Fine Distribution by Location (${period})`,
          font: { size: 18 },
        },
        legend: {
          display: false,
        },
      },
    },
  });
}

// Initial fetch
// fetchIssuedPlaceData();
