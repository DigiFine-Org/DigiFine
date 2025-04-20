document.addEventListener("DOMContentLoaded", function () {
  // Fetch fine data by location
  window.fetchFineData = function () {
    const timePeriod = document.getElementById("timePeriod").value;

    fetch(`get-fines.php?time_period=${timePeriod}`)
      .then((response) => response.json())
      .then((data) => {
        console.log("Fetched location data:", data);
        if (data.error) {
          alert(data.error);
        } else {
          const top30Data = data.slice(0, 30); // Use only the top 30 locations for the chart
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

    if (window.myChart) {
      window.myChart.destroy();
    }

    const labels = data.map((item) => item.label);
    const counts = data.map((item) => item.count);

    window.myChart = new Chart(ctx, {
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
          },
          legend: {
            display: false,
          },
        },
      },
    });
  }

  // Initial fetch
  fetchFineData();
});
