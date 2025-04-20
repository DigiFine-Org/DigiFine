window.fetchOfficerIssuedFineData = function () {
  const timePeriod = document.getElementById("timePeriod").value;
  const policeStationId = document.getElementById("policeStation").value;

  fetch(
    `officer-issued-fines/officer-get-fines.php?policeStation=${policeStationId}&time_period=${timePeriod}`
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched location data:", data);
      if (data.error) {
        s;
        alert(data.error);
      } else {
        const top30Data = data.slice(0, 30); // Use only the top 30
        updateOfficerIssuedChart(top30Data, timePeriod);
        updateFineSummary4(data, timePeriod);
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
};

function updateOfficerIssuedChart(data, period) {
  const ctx = document
    .getElementById("officerIssuedFineChart")
    .getContext("2d");

  if (window.officerIssuedFineChart3) {
    window.officerIssuedFineChart3.destroy();
  }

  const labels = data.map((item) => item.label);
  const counts = data.map((item) => item.count);

  window.officerIssuedFineChart3 = new Chart(ctx, {
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
            text: "Number of fines",
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
          text: `Number of Fines by Police Officer (${period})`,
        },
        legend: {
          display: false,
        },
      },
    },
  });
}
