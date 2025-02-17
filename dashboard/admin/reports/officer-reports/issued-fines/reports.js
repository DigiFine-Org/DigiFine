document.addEventListener("DOMContentLoaded", function () {
  // Ensure fetchFineData is globally accessible
  window.fetchFineData = function () {
    const officerId = document.getElementById("officerId").value;
    const timePeriod = document.getElementById("timePeriod").value;

    if (!officerId) {
      alert("Please enter a valid Officer ID.");
      return;
    }

    // Fetch the data from the PHP endpoint
    fetch(`fines-by-officer.php?police_id=${officerId}&period=${timePeriod}`)
      .then((response) => response.json())
      .then((data) => {
        console.log("Fetched data:", data); // Debugging log
        if (data.error) {
          alert(data.error);
        } else {
          updateChart(data, timePeriod);
        }
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };

  // Function to update the chart with the fetched data
  function updateChart(data, period) {
    const ctx = document.getElementById("fineChart").getContext("2d");

    // Destroy the previous chart if it exists
    if (window.myChart) {
      window.myChart.destroy();
    }

    let labels = [];
    let values = [];

    console.log("Updating chart for period:", period);

    if (period === "24h" || period === "72h") {
      // For 24h and 72h, generate hourly labels in local time.
      labels = getLastHours(period === "24h" ? 24 : 72);
    } else if (["7days", "14days", "30days", "90days"].includes(period)) {
      // For these day ranges, generate daily labels in "YYYY-MM-DD" format.
      labels = getLastDays(Number(period.replace("days", "")));
    } else if (period === "365days" || period === "lifetime") {
      // For lifetime, assume the backend returns data aggregated by month.
      labels = data.map((d) => d.label);
    }

    // Map the fetched data to our generated labels.
    // (If a label isn’t found in the data, default its count to 0.)
    values = labels.map((label) => {
      const found = data.find((d) => d.label === label);
      return found ? found.count : 0;
    });

    console.log("Labels:", labels);
    console.log("Values:", values);

    // Create the new chart
    window.myChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: labels,
        datasets: [
          {
            label: `Fines Issued (${period})`,
            data: values,
            backgroundColor: "rgba(54, 162, 235, 0.5)",
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 1,
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: { display: true, text: "Number of Fines" },
          },
          x: { title: { display: true, text: "Time Period" } },
        },
        plugins: {
          title: { display: true, text: `Fines Issued by Officer (${period})` },
        },
      },
    });
  }

  // Revised function to generate hourly labels for the last N hours.
  // • Aligns the current time to the start of the current hour.
  // • Then generates labels from (now - (hours-1)) to now.
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

  // Function to generate daily labels for the last N days in "YYYY-MM-DD" format.
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

  // Function to generate labels for the last 12 months (for 365 days representation).
  // This returns an array of month labels (e.g. "Feb 2025") for the past 12 months.
  // function getLast12Months() {
  //   const labels = [];
  //   const now = new Date();
  //   // Generate 12 months including the current month.
  //   for (let i = 11; i >= 0; i--) {
  //     const tempDate = new Date(now.getFullYear(), now.getMonth() - i, 1);
  //     const monthStr = tempDate.toLocaleString("en-GB", {
  //       month: "short",
  //       year: "numeric",
  //     });
  //     labels.push(monthStr);
  //   }
  //   return labels;
  // }
});
