function updateRevenueSummary(data, period) {
  const summaryDiv = document.getElementById("RevenueSummary");

  const { paid, issued, pending, overdue } = data.totals;

  const paidRate = issued === 0 ? 0 : ((paid / issued) * 100).toFixed(2);
  const pendingRate = issued === 0 ? 0 : ((pending / issued) * 100).toFixed(2);
  const overdueRate = issued === 0 ? 0 : ((overdue / issued) * 100).toFixed(2);

  // Format as LKR currency
  const formatLKR = (amount) =>
    new Intl.NumberFormat("en-LK", {
      style: "currency",
      currency: "LKR",
      minimumFractionDigits: 2,
    }).format(amount);

  const html = `
    <div class="summary-block">
      <h3>Fine Amount Analytics (${period})</h3>
      <ul>
        <li><strong>Total fine amount issued:</strong> ${formatLKR(issued)}</li>
        <li><strong>Paid amount:</strong> ${formatLKR(paid)} (${paidRate}%)</li>
        <li><strong>Pending amount:</strong> ${formatLKR(
          pending
        )} (${pendingRate}%)</li>
        <li><strong>Overdue amount:</strong> ${formatLKR(
          overdue
        )} (${overdueRate}%)</li>
      </ul>
    </div>
  `;

  summaryDiv.innerHTML = html;
}
