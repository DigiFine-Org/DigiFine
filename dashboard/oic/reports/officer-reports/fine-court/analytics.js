function updateCourtFineSummary(data, period) {
  const summaryDiv = document.getElementById("CourtfineSummary");

  // Convert string counts to integers
  const fineData = data.Fines.map((item) => ({
    ...item,
    count: parseInt(item.count, 10),
  }));

  const courtData = data.Court.map((item) => ({
    ...item,
    count: parseInt(item.count, 10),
  }));

  const totalFines = fineData.reduce((sum, item) => sum + item.count, 0);
  const totalCourt = courtData.reduce((sum, item) => sum + item.count, 0);
  const totalNonCourt = totalFines - totalCourt;

  const courtRate =
    totalFines === 0 ? 0 : ((totalCourt / totalFines) * 100).toFixed(2);

  const fineRate =
    totalFines === 0 ? 0 : ((totalNonCourt / totalFines) * 100).toFixed(2);

  const topEntry = fineData.reduce(
    (max, item) => (item.count > max.count ? item : max),
    { label: "", count: 0 }
  );

  const html = `
    <h4>Summary (${period})</h4>
    <ul>
      <li><strong>Total fines issued:</strong> ${totalFines}</li>
      <li><strong>Fines taken to court:</strong> ${totalCourt}</li>
      <li><strong>Fines paid directly:</strong> ${totalNonCourt}</li>
      <li><strong>Court offences rate:</strong> ${courtRate}%</li>
      <li><strong>Fine offences rate:</strong> ${fineRate}%</li>
    </ul>
  `;

  summaryDiv.innerHTML = html;
}
