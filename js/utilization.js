async function loadUtil(office = '', startDate = '', endDate = ''){
  const officeParam = new URLSearchParams({ office, start_date: startDate, end_date: endDate });
  fetch(`utilization-data.php?${encodeURI(officeParam)}}`)
    .then(res => res.json())
    .then(data => {
  
      const container = document.getElementById('utilizationContainer');
      if (!container) return;
  
      container.innerHTML = ''; // Bersihkan sebelum render ulang
  
      console.log(data)
      data.forEach((item, index) => {
        const number = index + 1;
        const label = item.type;
        const value = `${item.duration} menit`;
  
        const utilizationItem = document.createElement('div');
        utilizationItem.classList.add('utilization-item');
  
        utilizationItem.innerHTML = `
          <div class="utilization-number">${number}</div>
          <div class="utilization-label">${label}</div>
          <div class="utilization-value">${value}</div>
        `;
  
        const progressBar = document.createElement('div');
        progressBar.classList.add('progress-bar');
  
        const progress = document.createElement('div');
        progress.classList.add('progress');
        progress.style.width = `${item.progress}%`;
  
        progressBar.appendChild(progress);
        container.appendChild(utilizationItem);
        container.appendChild(progressBar);
      });
    })
    .catch(err => console.error("Fetch error:", err));
}



  document.addEventListener("DOMContentLoaded", () => {
    loadUtil();
  
    document.getElementById("filterButton").addEventListener("click", () => {
      const office = document.getElementById("officeFilter").value;
      const startDate = document.getElementById("startDate").value;
      const endDate = document.getElementById("endDate").value;
      loadUtil(office, startDate, endDate);
    });
  });
