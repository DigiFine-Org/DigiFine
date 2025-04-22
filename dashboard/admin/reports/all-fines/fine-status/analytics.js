function updateFineSummary(data, period) {
  const summaryDiv = document.getElementById("fineSummary");

  const totalPaid = data.paid.reduce((sum, item) => sum + item.count, 0);
  const totalPending = data.pending.reduce((sum, item) => sum + item.count, 0);
  const totalOverdue = data.overdue.reduce((sum, item) => sum + item.count, 0);

  const totalFines = totalPaid + totalPending + totalOverdue;

  const paidRate =
    totalFines === 0 ? 0 : ((totalPaid / totalFines) * 100).toFixed(2);
  const pendingRate =
    totalFines === 0 ? 0 : ((totalPending / totalFines) * 100).toFixed(2);
  const overdueRate =
    totalFines === 0 ? 0 : ((totalOverdue / totalFines) * 100).toFixed(2);

  // Combine all labels into one to detect peak label (hour/day)
  const allCombined = [...data.paid, ...data.pending, ...data.overdue];

  const groupedCounts = allCombined.reduce((map, item) => {
    map[item.label] = (map[item.label] || 0) + item.count;
    return map;
  }, {});

  let topEntry = { label: "", count: 0 };
  for (let label in groupedCounts) {
    if (groupedCounts[label] > topEntry.count) {
      topEntry = { label, count: groupedCounts[label] };
    }
  }

  const html = `
    <div class="summary-block">
      <h3>Analytics Summary (${period})</h3>
      <ul>
        <li><strong>Total fines issued:</strong> ${totalFines}</li>
        <li><strong>Paid fines:</strong> ${totalPaid} (${paidRate}%)</li>
        <li><strong>Pending fines:</strong> ${totalPending} (${pendingRate}%)</li>
        <li><strong>Overdue fines:</strong> ${totalOverdue} (${overdueRate}%)</li>
        <li><strong>Peak activity:</strong> ${topEntry.label} (${topEntry.count} fines)</li>
      </ul>
    </div>
  `;

  summaryDiv.innerHTML = html;
}
