// Fetch fine data by location
window.fetchStationFineData = function () {
  const timePeriod = document.getElementById("timePeriod").value;

  fetch(`police-stations/station-get-fines.php?time_period=${timePeriod}`)
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched location data:", data);
      if (data.error) {
        s;
        alert(data.error);
      } else {
        const top30Data = data.slice(0, 30); // Use only the top 30 locations for the chart
        updateStationChart(top30Data, timePeriod);
        updateFineSummary2(data, timePeriod);
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
};

function updateStationChart(data, period) {
  const ctx = document
    .getElementById("policeStationFineChart")
    .getContext("2d");

  if (window.fineChart2) {
    window.fineChart2.destroy();
  }

  const labels = data.map((item) => item.label);
  const counts = data.map((item) => item.count);

  window.fineChart2 = new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: `Fines by police station (${period})`,
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
            text: "Police Station",
          },
        },
      },
      plugins: {
        title: {
          display: true,
          text: `Revenue Distribution by Police Station (${period})`,
        },
        legend: {
          display: false,
        },
      },
    },
  });
}
