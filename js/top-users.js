let topUsersChart = null;

async function loadTopUser(office = '', startDate = '', endDate = ''){
  console.log("Top users");
  const officeParam = new URLSearchParams({ office, start_date: startDate, end_date: endDate });
  fetch(`top-users.php?${encodeURI(officeParam)}`)
    .then(res => res.json())
    .then(data => {
      const ctx = document.getElementById('topUsersChart').getContext('2d');
      console.log(data);

      // 🔥 Destroy existing chart if any
      if (topUsersChart) {
        topUsersChart.destroy();
      }

      topUsersChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Total Durasi (menit)',
            data: data.data,
            backgroundColor: '#FF4C4C',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return `${context.dataset.label}: ${context.raw} menit`;
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1,
                precision: 0
              }
            }
          }
        }
      });
    })
    .catch(console.error);
}

document.addEventListener("DOMContentLoaded", () => {
  console.log("loaded");
  loadTopUser();

  document.getElementById("filterButton").addEventListener("click", () => {
    const office = document.getElementById("officeFilter").value;
    const startDate = document.getElementById("startDate").value;
    const endDate = document.getElementById("endDate").value;
    loadTopUser(office, startDate, endDate);
  });
});
