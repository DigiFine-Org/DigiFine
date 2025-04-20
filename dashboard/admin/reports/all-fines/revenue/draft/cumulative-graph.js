document.addEventListener("DOMContentLoaded", function () {
  // Global chart reference
  let cumulativeChart = null;

  // Fetch and render chart data
  window.fetchCumulativeFineData = function () {
    const timePeriod = document.getElementById("timePeriod").value;

    fetch(`cumulative-graph-get-fines.php?time_period=${timePeriod}`)
      .then((response) => response.json())
      .then((data) => {
        console.log("Fetched data:", data);
        if (data.error) {
          alert(data.error);
        } else {
          updateCumulativeChart(data, timePeriod);
          updateFineSummary(data, timePeriod); // Optional summary update
        }
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };

  function updateCumulativeChart(data, period) {
    const ctx = document.getElementById("cumulativeChart").getContext("2d");

    if (cumulativeChart) {
      cumulativeChart.destroy();
    }

    // Determine x-axis labels
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

    // Convert each status into a map for efficient lookup
    const paidMap = new Map(data.paid.map((d) => [d.label, d.total_amount]));
    const pendingMap = new Map(
      data.pending.map((d) => [d.label, d.total_amount])
    );
    const overdueMap = new Map(
      data.overdue.map((d) => [d.label, d.total_amount])
    );

    // Fill missing labels with 0
    const paidFines = labels.map((label) => paidMap.get(label) || 0);
    const pendingFines = labels.map((label) => pendingMap.get(label) || 0);
    const overdueFines = labels.map((label) => overdueMap.get(label) || 0);

    // Draw chart
    cumulativeChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: labels,
        datasets: [
          {
            label: `Paid Fines (${period})`,
            data: paidFines,
            backgroundColor: "rgba(75, 192, 192, 0.2)",
            borderColor: "rgba(75, 192, 192, 1)",
            borderWidth: 2,
            tension: 0.1,
            fill: true,
          },
          {
            label: `Pending Fines (${period})`,
            data: pendingFines,
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 2,
            tension: 0.2,
            fill: true,
          },
          {
            label: `Overdue Fines (${period})`,
            data: overdueFines,
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
            title: {
              display: true,
              text: "Fine Amount (Rs.)",
            },
          },
          x: {
            title: {
              display: true,
              text: "Time",
            },
          },
        },
        plugins: {
          title: {
            display: true,
            text: `Fine Growth Comparison (${period})`,
          },
          legend: {
            position: "bottom",
          },
        },
      },
    });
  }

  function getLastHours(hours) {
    const labels = [];
    const now = new Date();
    for (let i = hours - 1; i >= 0; i--) {
      const date = new Date(now);
      date.setHours(now.getHours() - i);
      const label = `${date.getFullYear()}-${String(
        date.getMonth() + 1
      ).padStart(2, "0")}-${String(date.getDate()).padStart(2, "0")} ${String(
        date.getHours()
      ).padStart(2, "0")}:00`;
      labels.push(label);
    }
    return labels;
  }

  function getLastDays(days) {
    const labels = [];
    const now = new Date();
    for (let i = days - 1; i >= 0; i--) {
      const date = new Date(now);
      date.setDate(now.getDate() - i);
      const label = `${date.getFullYear()}-${String(
        date.getMonth() + 1
      ).padStart(2, "0")}-${String(date.getDate()).padStart(2, "0")}`;
      labels.push(label);
    }
    return labels;
  }
});
