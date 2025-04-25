window.fetchOffencesRevenueFineData = function () {
  const timePeriod = document.getElementById("timePeriod").value;
  const policeStationId = document.getElementById("stationId").value;

  fetch(
    `offence-type-revenue/get-offences-revenue.php?police_station=${policeStationId}&time_period=${timePeriod}`
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched location data:", data);
      if (data.error) {
        s;
        alert(data.error);
      } else {
        const top30Data = data.slice(0, 30); // Use only the top 30
        updateOffencesRevenueChart(top30Data, timePeriod);
        updateFineSummary6(data, timePeriod);
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
};

function updateOffencesRevenueChart(data, period) {
  const ctx = document.getElementById("OffenesRevenueChart").getContext("2d");

  if (window.offenceRevenueChart) {
    window.offenceRevenueChart.destroy();
  }

  const offenceNumber = data.map((item) => item.offence_number);
  const offenceName = data.map((item) => item.label);
  const counts = data.map((item) => item.count);

  window.offenceRevenueChart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: offenceNumber, // X-axis will show offenceNumber (like V001)
      datasets: [
        {
          label: `Fines by Offence Revenue(${period})`,
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
            text: "Offence",
          },
        },
      },
      plugins: {
        title: {
          display: true,
          text: `Total Fines - Offences (${period})`,
        },
        legend: {
          display: false,
        },
        tooltip: {
          callbacks: {
            title: function (tooltipItems) {
              const index = tooltipItems[0].dataIndex;
              const number = offenceNumber[index];
              const name = offenceName[index];
              return `${name} (${number})`; // e.g., Speeding (V001)
            },
            label: function (tooltipItem) {
              return `Count: ${tooltipItem.formattedValue}`;
            },
          },
        },
      },
    },
  });
}
// fetchOffencesRevenueFineData(); // on load display
