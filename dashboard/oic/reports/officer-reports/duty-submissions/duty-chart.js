window.fetchDutyData = function () {
  const officerId = document.getElementById("officerId").value;
  const timePeriod = document.getElementById("timePeriod").value;

  fetch(
    `duty-submissions/get-duty.php?police_id=${officerId}&time_period=${timePeriod}`
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched data:", data);
      if (data.error) {
        alert(data.error);
      } else {
        updateDutyChart(data, timePeriod);
        updateDutySummary(data, timePeriod);
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
};

function updateDutyChart(data, period) {
  const ctx = document.getElementById("dutyChart").getContext("2d");

  if (window.chart4) {
    window.chart4.destroy();
  }

  let labels = [];

  if (period === "24h" || period === "72h") {
    labels = getLastHours(period === "24h" ? 24 : 72);
  } else if (["7days", "14days", "30days", "90days"].includes(period)) {
    labels = getLastDays(Number(period.replace("days", "")));
  } else {
    const allDutyLabels = data.all_submissions.map((d) => d.label);
    const lateDutyLabels = data.late_submissions.map((d) => d.label);
    labels = Array.from(new Set([...allDutyLabels, ...lateDutyLabels])).sort();
  }

  const allDuty = new Map(data.all_submissions.map((d) => [d.label, d.count]));
  const lateDuty = new Map(
    data.late_submissions.map((d) => [d.label, d.count])
  );

  const all = labels.map((label) => allDuty.get(label) || 0);
  const late = labels.map((label) => lateDuty.get(label) || 0);

  window.chart4 = new Chart(ctx, {
    type: "line",
    data: {
      labels: labels,
      datasets: [
        {
          label: `all Duty Submissions`,
          data: all,
          backgroundColor: "rgba(158, 194, 220, 0.16)", // lighter fill
          borderColor: "rgba(54, 162, 235, 1)",
          borderWidth: 2,
          tension: 0,
          fill: true,
        },
        {
          label: `late submissions`,
          data: late,
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
          title: { display: true, text: "Number of all" },
        },
        x: {
          title: { display: true, text: "Time Period" },
        },
      },
      plugins: {
        title: {
          display: true,
          text: `Duty Submission Overview (${period})`,
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
