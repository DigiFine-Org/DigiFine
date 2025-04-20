window.fetchPieChartData = function () {
  const timePeriod = document.getElementById("timePeriod").value;

  fetch(`get-pie-chart-data.php?time_period=${timePeriod}`)
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched fine data:", data);
      if (data.error) {
        alert(data.error);
      } else {
        updateLocationChart(data, timePeriod); // No slicing since data is already simplified
        updateFineSummary(data, timePeriod); // Optional: your summary logic
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
};

function updateLocationChart(data, period) {
  const ctx = document.getElementById("pieChart").getContext("2d");

  if (window.chartPie) {
    window.chartPie.destroy();
  }

  const labels = data.map((item) => item.label);
  const counts = data.map((item) => parseInt(item.count));
  const total = counts.reduce((sum, count) => sum + count, 0);

  const backgroundColors = labels.map((label) => {
    if (label.toLowerCase() === "fine") {
      return "rgba(54, 163, 235, 0.66)";
    } else if (label.toLowerCase() === "court") {
      return "rgba(255, 99, 133, 0.66)";
    }
    return "rgba(75, 192, 192, 0.6)";
  });

  window.chartPie = new Chart(ctx, {
    type: "pie",
    data: {
      labels: labels,
      datasets: [
        {
          label: `Fines by offence type (${period})`,
          data: counts,
          backgroundColor: backgroundColors,
          borderColor: "#fff",
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: `Fine Distribution by Offence Type (${period})`,
        },
        legend: {
          display: true,
          position: "bottom",
        },
        datalabels: {
          color: "#fff",
          formatter: (value, context) => {
            const total = context.chart.data.datasets[0].data.reduce(
              (acc, val) => acc + val,
              0
            );
            const percentage = total > 0 ? (value / total) * 100 : 0;
            return `${percentage.toFixed(1)}%`;
          },
          font: {
            weight: "bold",
            size: 14,
          },
        },
      },
    },
    plugins: [ChartDataLabels],
  });
}
