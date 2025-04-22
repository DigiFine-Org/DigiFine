window.fetchOfficerFineData = function () {
  const timePeriod = document.getElementById("timePeriod").value;

  fetch(`police-officers/officer-get-fines.php?time_period=${timePeriod}`)
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched location data:", data);
      if (data.error) {
        s;
        alert(data.error);
      } else {
        const top30Data = data.slice(0, 30); // Use only the top 30
        updateOfficerChart(top30Data, timePeriod);
        updateFineSummary3(data, timePeriod);
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
};

function updateOfficerChart(data, period) {
  const ctx = document.getElementById("officerFineChart").getContext("2d");

  if (window.officerFineChart3) {
    window.officerFineChart3.destroy();
  }

  const labels = data.map((item) => item.label);
  const counts = data.map((item) => item.count);

  window.officerFineChart3 = new Chart(ctx, {
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
          text: `Revenue Distribution by Police Officer (${period})`,
        },
        legend: {
          display: false,
        },
      },
    },
  });
}
fetchOfficerFineData(); // on load display
