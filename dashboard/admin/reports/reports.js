document.addEventListener("DOMContentLoaded", function () {
  // Function to fetch fine data
  function fetchFineData() {
    const officerId = document.getElementById("officerId").value;
    const timePeriod = document.getElementById("timePeriod").value;

    if (!officerId) {
      alert("Please enter a valid Officer ID.");
      return;
    }

    fetch(`fines-by-officer.php?police_id=${officerId}&period=${timePeriod}`)
      .then((response) => response.json())
      .then((data) => {
        console.log("Fetched data:", data); // Debugging
        if (data.error) {
          alert(data.error);
        } else {
          updateChart(data, timePeriod);
        }
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  }

  // Function to update the chart with new data
  function updateChart(data, period) {
    const ctx = document.getElementById("fineChart").getContext("2d");

    // Destroy the previous chart to avoid overlap
    if (window.myChart) {
      window.myChart.destroy();
    }

    // Format labels and data based on the time period
    let labels = [];
    let values = [];

    if (period === "24h" || period === "72h") {
      labels = getLastHours(period === "24h" ? 24 : 72);
      values = labels.map((label) => {
        const found = data.find((d) => {
          const date = new Date(d.label);
          const formattedDate = date.toLocaleString("en-GB", {
            hour: "2-digit",
            hour12: false,
          });
          return formattedDate === label;
        });
        return found ? found.value : 0;
      });
    } else if (
      period === "7days" ||
      period === "14days" ||
      period === "30days" ||
      period === "90days"
    ) {
      labels = getLastDays(
        period === "7days"
          ? 7
          : period === "14days"
          ? 14
          : period === "30days"
          ? 30
          : 90
      );
      values = labels.map((label) => {
        const found = data.find((d) => {
          const date = new Date(d.label);
          const formattedDate = date.toLocaleString("en-GB", {
            day: "numeric",
            month: "short",
          });
          return formattedDate === label;
        });
        return found ? found.value : 0;
      });
    } else if (period === "365days") {
      labels = getLast12Months();
      values = labels.map((label) => {
        const found = data.find((d) => {
          const date = new Date(d.label);
          const formattedDate = date.toLocaleString("en-GB", {
            month: "short",
            year: "numeric",
          });
          return formattedDate === label;
        });
        return found ? found.value : 0;
      });
    } else if (period === "lifetime") {
      labels = ["Lifetime"];
      values = [data[0]?.value || 0];
    }

    console.log("Labels:", labels); // Debugging
    console.log("Values:", values); // Debugging

    // Create a new chart with the fetched data
    window.myChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            label: `${
              period.charAt(0).toUpperCase() + period.slice(1)
            } Fines Issued`,
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
            title: {
              display: true,
              text: "Number of Fines",
            },
          },
          x: {
            title: {
              display: true,
              text: period.charAt(0).toUpperCase() + period.slice(1),
            },
          },
        },
        plugins: {
          title: {
            display: true,
            text: `Fines Issued by Officer (${
              period.charAt(0).toUpperCase() + period.slice(1)
            })`,
          },
        },
      },
    });
  }

  // Helper function to get the last X hours
  function getLastHours(hours) {
    const labels = [];
    const date = new Date();
    for (let i = hours - 1; i >= 0; i--) {
      const tempDate = new Date(
        date.getFullYear(),
        date.getMonth(),
        date.getDate(),
        date.getHours() - i
      );
      labels.push(
        tempDate.toLocaleString("en-GB", {
          hour: "2-digit",
          hour12: false,
        })
      );
    }
    return labels;
  }

  // Helper function to get the last X days
  function getLastDays(days) {
    const labels = [];
    const date = new Date();
    for (let i = days - 1; i >= 0; i--) {
      const tempDate = new Date(
        date.getFullYear(),
        date.getMonth(),
        date.getDate() - i
      );
      labels.push(
        tempDate.toLocaleString("en-GB", { day: "numeric", month: "short" })
      );
    }
    return labels;
  }

  // Helper function to get the last 12 months
  function getLast12Months() {
    const months = [];
    const date = new Date();
    for (let i = 11; i >= 0; i--) {
      const tempDate = new Date(date.getFullYear(), date.getMonth() - i, 1);
      months.push(
        tempDate.toLocaleString("en-GB", { month: "short", year: "numeric" })
      );
    }
    return months;
  }

  // Expose fetchFineData to the global scope for onclick
  window.fetchFineData = fetchFineData;
});
