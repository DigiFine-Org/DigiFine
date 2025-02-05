document.addEventListener("DOMContentLoaded", function () {
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

  function updateChart(data, period) {
    const ctx = document.getElementById("fineChart").getContext("2d");

    if (window.myChart) {
      window.myChart.destroy();
    }

    let labels = [];
    let values = [];

    if (period === "24h" || period === "72h") {
      labels = getLastHours(period === "24h" ? 24 : 72);
      values = labels.map((label) => {
        const found = data.find((d) => {
          const date = new Date(d.label);
          const formattedDate = `${date.getDate()} ${date.toLocaleString(
            "en-GB",
            { month: "short" }
          )}, ${date.getHours().toString().padStart(2, "0")}:00`;
          return formattedDate === label;
        });
        return found ? found.value : 0;
      });
    } else if (["7days", "14days", "30days", "90days"].includes(period)) {
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
          const formattedDate = `${date.getDate()} ${date.toLocaleString(
            "en-GB",
            { month: "short" }
          )}`;
          return formattedDate === label;
        });
        return found ? found.value : 0;
      });
    } else if (period === "365days") {
      labels = getLast12Months();
      values = labels.map((label) => {
        const found = data.find((d) => {
          const date = new Date(d.label);
          const formattedDate = `${date.toLocaleString("en-GB", {
            month: "short",
          })} ${date.getFullYear()}`;
          return formattedDate === label;
        });
        return found ? found.value : 0;
      });
    } else if (period === "lifetime") {
      labels = getLifetimeLabels(data);
      values = data.map((d) => d.value);
    }

    console.log("Labels:", labels);
    console.log("Values:", values);

    window.myChart = new Chart(ctx, {
      type: "line", // Other types: 'bar', 'pie', 'doughnut', 'radar', 'polarArea', 'bubble', 'scatter'
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

  function getLastHours(hours) {
    const labels = [];
    const now = new Date();
    for (let i = hours - 1; i >= 0; i--) {
      const tempDate = new Date(now);
      tempDate.setHours(now.getHours() - i);
      labels.push(
        `${tempDate.getDate()} ${tempDate.toLocaleString("en-GB", {
          month: "short",
        })}, ${tempDate.getHours().toString().padStart(2, "0")}:00`
      );
    }
    return labels;
  }

  function getLastDays(days) {
    const labels = [];
    const now = new Date();
    for (let i = days - 1; i >= 0; i--) {
      const tempDate = new Date(now);
      tempDate.setDate(now.getDate() - i);
      labels.push(
        `${tempDate.getDate()} ${tempDate.toLocaleString("en-GB", {
          month: "short",
        })}`
      );
    }
    return labels;
  }

  function getLast12Months() {
    const months = [];
    const now = new Date();
    for (let i = 11; i >= 0; i--) {
      const tempDate = new Date(now.getFullYear(), now.getMonth() - i, 1);
      months.push(
        `${tempDate.toLocaleString("en-GB", {
          month: "short",
        })} ${tempDate.getFullYear()}`
      );
    }
    return months;
  }

  function getLifetimeLabels(data) {
    const labels = [];
    const now = new Date();
    for (let i = 0; i < data.length; i++) {
      const date = new Date(data[i].label);
      labels.push(
        `${date.getDate()} ${date.toLocaleString("en-GB", {
          month: "short",
        })} ${date.getFullYear()}`
      );
    }
  }
  window.fetchFineData = fetchFineData;
});
