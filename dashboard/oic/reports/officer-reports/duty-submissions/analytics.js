function updateDutySummary(data, period) {
  const summaryDiv = document.getElementById("DutySummary");

  // Convert string counts to integers
  const allSubmissions = data.all_submissions.map((item) => ({
    ...item,
    count: parseInt(item.count, 10),
  }));

  const lateSubmissions = data.late_submissions.map((item) => ({
    ...item,
    count: parseInt(item.count, 10),
  }));

  const totalSubmissions = allSubmissions.reduce(
    (sum, item) => sum + item.count,
    0
  );
  const totalLate = lateSubmissions.reduce((sum, item) => sum + item.count, 0);
  const totalOnTime = totalSubmissions - totalLate;

  const lateRate =
    totalSubmissions === 0
      ? 0
      : ((totalLate / totalSubmissions) * 100).toFixed(2);

  const onTimeRate =
    totalSubmissions === 0
      ? 0
      : ((totalOnTime / totalSubmissions) * 100).toFixed(2);

  const topEntry = allSubmissions.reduce(
    (max, item) => (item.count > max.count ? item : max),
    { label: "", count: 0 }
  );

  const html = `
    <h4>Summary (${period})</h4>
    <ul>
      <li><strong>Total submissions:</strong> ${totalSubmissions}</li>
      <li><strong>Late submissions:</strong> ${totalLate}</li>
      <li><strong>On-time submissions:</strong> ${totalOnTime}</li>
      <li><strong>Late submission rate:</strong> ${lateRate}%</li>
      <li><strong>On-time submission rate:</strong> ${onTimeRate}%</li>
    </ul>
  `;

  summaryDiv.innerHTML = html;
}
