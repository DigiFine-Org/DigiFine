function updateIssuedFineSummary(data, period) {
  const summaryDiv = document.getElementById("IssuedFineSummary");

  const totalAll = data.all.reduce((sum, item) => sum + item.count, 0);
  const totalReported = data.reported.reduce(
    (sum, item) => sum + item.count,
    0
  );
  const totalUnreported = totalAll - totalReported;
  const reportingRate =
    totalAll === 0 ? 0 : ((totalReported / totalAll) * 100).toFixed(2);

  // Find the label (e.g. day/hour) with the most fines
  const topEntry = data.all.reduce(
    (max, item) => (item.count > max.count ? item : max),
    { label: "", count: 0 }
  );

  const html = `
    <h4>Summary (${period})</h4>
    <ul>
      <li><strong>Total fines issued:</strong> ${totalAll}</li>
      <li><strong>Total reported fines:</strong> ${totalReported}</li>
      <li><strong>Unreported fines:</strong> ${totalUnreported}</li>
      <li><strong>Reporting rate:</strong> ${reportingRate}%</li>
      <li><strong>Peak activity:</strong> ${topEntry.label} (${topEntry.count} fines)</li>
    </ul>
  `;

  summaryDiv.innerHTML = html;
}
