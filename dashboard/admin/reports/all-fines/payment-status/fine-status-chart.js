window.fetchFineStatusData = function () {
  const timePeriod = document.getElementById("timePeriod").value;

  fetch(`payment-status/get-fines.php?time_period=${timePeriod}`)
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched data:", data);
      if (data.error) {
        alert(data.error);
      } else {
        updateFineStatuChart(data, timePeriod);
        updateFineSummary(data, timePeriod);
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
};

function updateFineStatuChart(data, period) {
  const ctx = document.getElementById("fineChart").getContext("2d");

  if (window.fineStatusChart) {
    window.fineStatusChart.destroy();
  }

  let labels = [];

  if (period === "24h" || period === "72h") {
    labels = getLastHours(period === "24h" ? 24 : 72);
  } else if (
    ["7days", "14days", "30days", "90days", "365days"].includes(period)
  ) {
    labels = getLastDays(Number(period.replace("days", "")));
  } else {
    const paidLabels = data.paid.map((d) => d.label);
    const pendingLabels = data.pending.map((d) => d.label);
    const overdueLabels = data.overdue.map((d) => d.label);
    labels = Array.from(
      new Set([...paidLabels, ...pendingLabels, ...overdueLabels])
    ).sort();
  }

  // Create maps for faster lookup
  const paidMap = new Map(data.paid.map((d) => [d.label, d.count]));
  const pendingMap = new Map(data.pending.map((d) => [d.label, d.count]));
  const overdueMap = new Map(data.overdue.map((d) => [d.label, d.count]));

  const paidFines = labels.map((label) => paidMap.get(label) || 0);
  const pendingFines = labels.map((label) => pendingMap.get(label) || 0);
  const overdueFines = labels.map((label) => overdueMap.get(label) || 0);

  window.fineStatusChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: labels,
      datasets: [
        {
          label: `Paid Fines`,
          data: paidFines,
          backgroundColor: "rgba(75, 192, 192, 0.13)",
          borderColor: "rgba(75, 192, 192, 1)",
          borderWidth: 2,
          tension: 0.1,
          fill: true,
        },
        {
          label: `Pending Fine`,
          data: pendingFines,
          backgroundColor: "rgba(54, 163, 235, 0.13)",
          borderColor: "rgba(54, 162, 235, 1)",
          borderWidth: 2,
          tension: 0.2,
          fill: true,
        },
        {
          label: `Overdue Fines`,
          data: overdueFines,
          backgroundColor: "rgba(255, 99, 132, 0.13)",
          borderColor: "rgba(255, 99, 132, 1)",
          borderWidth: 3,
          tension: 0.3,
          fill: true,
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          title: { display: true, text: "Number of Fines", font: { size: 16 } },
        },
        x: {
          title: { display: true, text: "Time Period", font: { size: 16 } },
        },
      },
      plugins: {
        title: {
          display: true,
          text: `Payment Status`,
          font: { size: 18 },
        },
        legend: {
          display: true,
          position: "top",
          labels: {},
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

fetchFineStatusData(); // Initial fetch
