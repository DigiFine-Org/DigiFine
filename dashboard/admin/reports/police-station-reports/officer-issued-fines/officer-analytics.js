function updateFineSummary4(data, period) {
  const summaryDiv = document.getElementById("officerIssuedSummary");

  if (!data || data.length === 0) {
    summaryDiv.innerHTML =
      "<li>No fine data available for the selected period.</li>";
    return;
  }

  const totalAll = data.reduce((sum, loc) => sum + Number(loc.count), 0);

  // Since the array is ordered, the first location is the top one
  const topLocation = data[0];

  // Create summary list items
  const summaryHTML = `
        <h3>Fine Amount Analytics (${period})</h3>
  <ul>
    <li><strong>Top police officer:</strong> ${topLocation.label} (${topLocation.count} fines)</li>
    <li><strong>Time period:</strong> ${period}</li>
    <li><strong>Total fines issued:</strong> ${totalAll}</li>
    <li><strong>Number of officers:</strong> ${data.length}</li>
    
  </ul>
  `;

  summaryDiv.innerHTML = summaryHTML;
}
