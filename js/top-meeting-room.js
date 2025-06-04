// Tambahkan variabel global untuk menyimpan instance chart
let topRoomChartInstance = null;

// Ambil parameter office dari URL
async function loadTopMeeting(office = 'semua', startDate = '', endDate = '') {
  const officeParam = new URLSearchParams({ office, start_date: startDate, end_date: endDate });

  console.log("office params:")
  console.log(encodeURI(officeParam))

  // Fetch data dari server dengan parameter lokasi kantor
  fetch(`top-meeting-room.php?${officeParam}`)
    .then(res => {
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }
      return res.json();
    })
    .then(data => {
      const chartContainer = document.getElementById('topRoomChart').getContext('2d');

      // Jika tidak ada data, tampilkan pesan
      if (!data || data.labels.length === 0 || data.values.length === 0) {
        console.log("No data");
        chartContainer.canvas.parentNode.innerHTML = "<p style='text-align:center; color:gray;'>Tidak ada data untuk lokasi ini.</p>";
        return;
      }

      console.log("top meeting : ")
      console.log(data.values)

      // Destroy chart sebelumnya jika ada
      if (topRoomChartInstance) {
        topRoomChartInstance.destroy();
      }

      // Inisialisasi chart Pie
      topRoomChartInstance = new Chart(chartContainer, {
        type: 'pie',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Jumlah Booking',
            data: data.values,
            backgroundColor: [
              '#FF4C4C', '#FF6B6B', '#FF8C8C',
              '#FF9999', '#FFB3B3', '#CC0000', '#990000'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { position: 'bottom' },
            tooltip: {
              callbacks: {
                label: function (context) {
                  return `${context.label}: ${context.raw} booking`;
                }
              }
            }
          }
        }
      });
    })
    .catch(error => {
      console.error('Gagal mengambil data top meeting room:', error);
      const chartContainer = document.getElementById('topRoomChart').getContext('2d');
      chartContainer.canvas.parentNode.innerHTML = "<p style='text-align:center; color:red;'>Terjadi kesalahan saat mengambil data.</p>";
    });
}

document.addEventListener("DOMContentLoaded", () => {
  loadTopMeeting();

  document.getElementById("filterButton").addEventListener("click", () => {
    const office = document.getElementById("officeFilter").value;
    const startDate = document.getElementById("startDate").value;
    const endDate = document.getElementById("endDate").value;
    loadTopMeeting(office, startDate, endDate);
  });
});
