window.fetchFineCourtData = function () {
  const officerId = document.getElementById("officerId").value;
  const timePeriod = document.getElementById("timePeriod").value;

  fetch(
    `fine-court/get-fine-court.php?police_id=${officerId}&time_period=${timePeriod}`
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched data:", data);
      if (data.error) {
        alert(data.error);
      } else {
        updateChart(data, timePeriod);
        updateCourtFineSummary(data, timePeriod);
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
};

function updateChart(data, period) {
  const ctx = document.getElementById("fineCourtChart").getContext("2d");

  if (window.chart2) {
    window.chart2.destroy();
  }

  let labels = [];

  if (period === "24h" || period === "72h") {
    labels = getLastHours(period === "24h" ? 24 : 72);
  } else if (["7days", "14days", "30days", "90days"].includes(period)) {
    labels = getLastDays(Number(period.replace("days", "")));
  } else {
    const fineLabels = data.Fines.map((d) => d.label);
    const courtLabels = data.Court.map((d) => d.label);
    labels = Array.from(new Set([...fineLabels, ...courtLabels])).sort();
  }

  const fineMap = new Map(data.Fines.map((d) => [d.label, d.count]));
  const courtdMap = new Map(data.Court.map((d) => [d.label, d.count]));

  const fines = labels.map((label) => fineMap.get(label) || 0);
  const court = labels.map((label) => courtdMap.get(label) || 0);

  window.chart2 = new Chart(ctx, {
    type: "line",
    data: {
      labels: labels,
      datasets: [
        {
          label: `Fines (${period})`,
          data: fines,
          backgroundColor: "rgba(158, 194, 220, 0.16)", // lighter fill
          borderColor: "rgba(54, 162, 235, 1)",
          borderWidth: 2,
          tension: 0,
          fill: true,
        },
        {
          label: `Court Cases (${period})`,
          data: court,
          backgroundColor: "rgba(249, 189, 202, 0.35)", // no fill
          borderColor: "rgba(255, 99, 132, 1)",
          borderWidth: 2,
          // borderDash: [5, 5], // dashed line
          tension: 0.1,
          fill: true,
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          title: { display: true, text: "Number of Fines" },
        },
        x: {
          title: { display: true, text: "Time Period" },
        },
      },
      plugins: {
        title: {
          display: true,
          text: `Fines Overview (${period})`,
        },
        legend: {
          display: true,
        },
      },
    },
  });
}

function getLastHours(hours) {
  const labels = [];
  const now = new Date();
  for (let i = hours - 1; i >= 0; i--) {
    let tempDate = new Date(now);
    tempDate.setHours(now.getHours() - i);
    const year = tempDate.getFullYear();
    const month = String(tempDate.getMonth() + 1).padStart(2, "0");
    const day = String(tempDate.getDate()).padStart(2, "0");
    const hour = String(tempDate.getHours()).padStart(2, "0");
    labels.push(`${year}-${month}-${day} ${hour}:00`);
  }
  return labels;
}

function getLastDays(days) {
  const labels = [];
  const now = new Date();
  for (let i = days - 1; i >= 0; i--) {
    let tempDate = new Date(now);
    tempDate.setDate(now.getDate() - i);
    const year = tempDate.getFullYear();
    const month = String(tempDate.getMonth() + 1).padStart(2, "0");
    const day = String(tempDate.getDate()).padStart(2, "0");
    labels.push(`${year}-${month}-${day}`);
  }
  return labels;
}

// fetchFineCourtData(); // Initial fetch
