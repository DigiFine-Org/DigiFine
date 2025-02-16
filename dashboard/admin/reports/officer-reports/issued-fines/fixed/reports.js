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

    // Ensure that the labels are formatted consistently
    console.log("Updating chart for period:", period);

    if (["24h", "72h"].includes(period)) {
      // For 24h and 72h, generate the last X hours with time
      labels = getLastHours(period === "24h" ? 24 : 72);
    } else if (["7days", "14days", "30days", "90days"].includes(period)) {
      // For days, generate labels based on the last N days
      labels = getLastDays(Number(period.replace("days", "")));
    } else if (period === "365days") {
      // For 365days, just use the fetched data directly
      labels = data.map((d) => formatLabel(d.label));
    } else if (period === "lifetime") {
      // For lifetime, map labels and values directly from data
      labels = data.map((d) => formatLabel(d.label));
    }

    // Map values to match the fetched data based on the period
    values = labels.map((label) => {
      const found = data.find((d) => formatLabel(d.label) === label);
      return found ? found.count : 0;
    });

    // Log the labels and values for debugging
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

  // Function to generate labels for the last N hours
  function getLastHours(hours) {
    const labels = [];
    const now = new Date();
    for (let i = hours - 1; i >= 0; i--) {
      let tempDate = new Date(now);
      tempDate.setHours(now.getHours() - i);
      // Format label to include date and hour (e.g., 2025-02-17 01:00:00)
      labels.push(
        tempDate.toISOString().split("T")[0] +
          " " +
          tempDate.getHours().toString().padStart(2, "0") +
          ":00:00"
      );
    }
    return labels;
  }

  // Function to generate labels for the last N days
  function getLastDays(days) {
    const labels = [];
    const now = new Date();
    for (let i = days - 1; i >= 0; i--) {
      let tempDate = new Date(now);
      tempDate.setDate(now.getDate() - i);
      labels.push(tempDate.toLocaleDateString("en-GB"));
    }
    return labels;
  }

  // Function to generate labels for the last 12 months
  function getLast12Months() {
    const labels = [];
    const now = new Date();
    for (let i = 11; i >= 0; i--) {
      let tempDate = new Date(now.getFullYear(), now.getMonth() - i, 1);
      labels.push(
        tempDate.toLocaleString("en-GB", { month: "short", year: "numeric" })
      );
    }
    return labels;
  }

  // Helper function to format label to YYYY-MM-DD for consistency
  function formatLabel(label) {
    const date = new Date(label);
    // If it's a timestamp with time, return the full date-time (e.g., 2025-02-17 01:00:00)
    return label.includes(":") ? label : date.toLocaleDateString("en-GB"); // Formats as 'YYYY-MM-DD' or 'YYYY-MM-DD HH:00:00'
  }
});
