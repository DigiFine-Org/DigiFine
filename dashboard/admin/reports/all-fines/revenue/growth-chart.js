function drawFineAmountGrowthChart(data, timePeriod) {
  const paidData = data.paid || [];
  const pendingData = data.pending || [];
  const overdueData = data.overdue || [];

  // Combine all labels and sort them
  const labelsSet = new Set();
  [...paidData, ...pendingData, ...overdueData].forEach((entry) =>
    labelsSet.add(entry.label)
  );
  const sortedLabels = Array.from(labelsSet).sort();

  // Helper to map data into a dictionary
  function mapData(dataArray) {
    const map = {};
    dataArray.forEach((entry) => {
      map[entry.label] = parseFloat(entry.total_amount);
    });
    return map;
  }

  const paidMap = mapData(paidData);
  const pendingMap = mapData(pendingData);
  const overdueMap = mapData(overdueData);

  // Create cumulative arrays
  let cumPaid = 0,
    cumPending = 0,
    cumOverdue = 0;
  const cumulativePaid = [],
    cumulativePending = [],
    cumulativeOverdue = [];

  sortedLabels.forEach((label) => {
    cumPaid += paidMap[label] || 0;
    cumPending += pendingMap[label] || 0;
    cumOverdue += overdueMap[label] || 0;

    cumulativePaid.push(cumPaid);
    cumulativePending.push(cumPending);
    cumulativeOverdue.push(cumOverdue);
  });

  // Destroy existing chart if exists
  if (window.fineGrowthChartInstance) {
    window.fineGrowthChartInstance.destroy();
  }

  const ctx = document.getElementById("fineGrowthChart").getContext("2d");
  window.fineGrowthChartInstance = new Chart(ctx, {
    type: "line",
    data: {
      labels: sortedLabels,
      datasets: [
        {
          label: `Paid Fines (${timePeriod})`,
          data: cumulativePaid,
          backgroundColor: "rgba(75, 192, 192, 0.2)",
          borderColor: "rgba(75, 192, 192, 1)",
          borderWidth: 2,
          tension: 0.1,
          fill: true,
        },
        {
          label: `Pending Fines (${timePeriod})`,
          data: cumulativePending,
          backgroundColor: "rgba(54, 162, 235, 0.2)",
          borderColor: "rgba(54, 162, 235, 1)",
          borderWidth: 2,
          tension: 0.2,
          fill: true,
        },
        {
          label: `Overdue Fines (${timePeriod})`,
          data: cumulativeOverdue,
          backgroundColor: "rgba(255, 99, 132, 0.2)",
          borderColor: "rgba(255, 99, 132, 1)",
          borderWidth: 2,
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
          title: { display: true, text: "Fine Amount (Rs.)" },
        },
        x: {
          title: { display: true, text: "Time Period" },
        },
      },
      plugins: {
        title: {
          display: true,
          text: `Fine Amount Overview (${timePeriod})`,
        },
        legend: {
          display: true,
          position: "bottom",
        },
      },
    },
  });
}
