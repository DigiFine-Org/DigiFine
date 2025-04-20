document.addEventListener("DOMContentLoaded", function () {
  // Fetch fine data by location
  window.fetchFineData = function () {
    const timePeriod = document.getElementById("timePeriod").value;

    fetch(`get-offences.php?time_period=${timePeriod}`)
      .then((response) => response.json())
      .then((data) => {
        console.log("Fetched fine data:", data);
        if (data.error) {
          alert(data.error);
        } else {
          const top30Data = data.slice(0, 30); // Use only the top 30
          updateLocationChart(top30Data, timePeriod);
          updateFineSummary(data, timePeriod);
        }
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };

  function updateLocationChart(data, period) {
    const ctx = document.getElementById("fineChart").getContext("2d");

    if (window.chart1) {
      window.chart1.destroy();
    }

    const labels = data.map((item) => item.label);
    const counts = data.map((item) => item.count);

    window.chart1 = new Chart(ctx, {
      type: "bar",
      data: {
        labels: labels,
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
        },
      },
    });
  }
});
