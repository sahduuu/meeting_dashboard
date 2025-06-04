// Fungsi untuk memuat data ringkasan dari get_summary.php
async function loadSummaryStats(office = 'semua', startDate = '', endDate = '') {
    try {
        const params = new URLSearchParams({ office, start_date: startDate, end_date: endDate });
        console.log("Start Date")
        console.log(startDate);
        const response = await fetch(`get_summary.php?${params}`);
        const data = await response.json();
        console.log(params, "params"),
console.log(response, "response"),
console.log(data, "data")

        // Tampilkan hasil ke elemen HTML
        document.getElementById("totalBookings").textContent = data.bookings;
        document.getElementById("hoursBooked").textContent = data.hours_booked + " jam";
        document.getElementById("unbookedCapacity").textContent = data.unbooked_capacity + "%";
        document.getElementById("peakHour").textContent = data.peak_hour;

    } catch (error) {
        console.error("Gagal memuat summary stats:", error);
    }
}

// Jalankan saat halaman sudah siap
document.addEventListener("DOMContentLoaded", () => {
    loadSummaryStats(); // default load: semua kantor & semua tanggal

    // Jika ada filter, panggil ulang
    document.getElementById("filterButton").addEventListener("click", () => {
        const office = document.getElementById("officeFilter").value;
        const startDate = document.getElementById("startDate").value;
        const endDate = document.getElementById("endDate").value;
        loadSummaryStats(office, startDate, endDate);
    });
});
