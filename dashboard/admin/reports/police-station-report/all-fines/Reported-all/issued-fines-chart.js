window.fetchIssuedFineData = function () {
  const timePeriod = document.getElementById("timePeriod").value;
  const policeStationId = document.getElementById("stationId").value;

  fetch(
    `reported-all/get-fines.php?police_station=${policeStationId}&time_period=${timePeriod}`
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched data:", data);
      if (data.error) {
        alert(data.error);
      } else {
        updateIssuedFIneChart(data, timePeriod);
        updateIssuedFineSummary(data, timePeriod);
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
};

function updateIssuedFIneChart(data, period) {
  const ctx = document.getElementById("issuedFineChart").getContext("2d");

  if (window.issuedFIneChart) {
    window.issuedFIneChart.destroy();
  }

  let labels = [];

  if (period === "24h" || period === "72h") {
    labels = getLastHours(period === "24h" ? 24 : 72);
  } else if (["7days", "14days", "30days", "90days"].includes(period)) {
    labels = getLastDays(Number(period.replace("days", "")));
  } else {
    const allLabels = data.all.map((d) => d.label);
    const reportedLabels = data.reported.map((d) => d.label);
    labels = Array.from(new Set([...allLabels, ...reportedLabels])).sort();
  }

  const allMap = new Map(data.all.map((d) => [d.label, d.count]));
  const reportedMap = new Map(data.reported.map((d) => [d.label, d.count]));

  const allFines = labels.map((label) => allMap.get(label) || 0);
  const reportedFines = labels.map((label) => reportedMap.get(label) || 0);

  window.issuedFIneChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: labels,
      datasets: [
        {
          label: `All Fines (${period})`,
          data: allFines,
          backgroundColor: "rgba(158, 194, 220, 0.16)", // lighter fill
          borderColor: "rgba(54, 162, 235, 1)",
          borderWidth: 2,
          tension: 0,
          fill: true,
        },
        {
          label: `Reported Fines (${period})`,
          data: reportedFines,
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
          text: `Reported Fines and All Issued Fines (${period})`,
          font: { size: 18 },
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

// fetchIssuedFineData(); // Initial fetch
