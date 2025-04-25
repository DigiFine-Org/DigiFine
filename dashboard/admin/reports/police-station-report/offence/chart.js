window.fetchFineData = function () {
  const timePeriod = document.getElementById("timePeriod").value;
  const policeStationId = document.getElementById("stationId").value;

  fetch(
    `get-offences.php?police_station=${policeStationId}&time_period=${timePeriod}`
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched fine data:", data);
      if (data.error) {
        alert(data.error);
      } else {
        const top30Data = data.slice(0, 30); // Use only the top 30
        updateOffencesChart(top30Data, timePeriod);
        updateFineSummary(data, timePeriod);
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
};

function updateOffencesChart(data, period) {
  const ctx = document.getElementById("offenceChart").getContext("2d");

  if (window.chart1) {
    window.chart1.destroy();
  }

  const offenceNumber = data.map((item) => item.offence_number);
  const labels = data.map((item) => item.label);
  const counts = data.map((item) => item.count);

  window.chart1 = new Chart(ctx, {
    type: "bar",
    data: {
      labels: offenceNumber,
      datasets: [
        {
          label: `Fines by offence (${period})`,
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
            text: "Offence",
          },
        },
      },
      plugins: {
        title: {
          display: true,
          text: `Fine Distribution by Offence (${period})`,
        },
        legend: {
          display: false,
        },
        tooltip: {
          callbacks: {
            title: function (tooltipItems) {
              const index = tooltipItems[0].dataIndex;
              const number = offenceNumber[index];
              const name = labels[index];
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
// fetchFineData(); // on load display
