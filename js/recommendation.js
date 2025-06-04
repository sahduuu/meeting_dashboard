fetch('recommendation.php')
  .then(res => res.json())
  .then(data => {
    const list = document.getElementById('recommendationList');
    if (data.length === 0) {
      list.innerHTML = "<li>Tidak ada rekomendasi saat ini.</li>";
    } else {
      list.innerHTML = data.map(item => `<li>${item}</li>`).join('');
    }
  })
  .catch(console.error);

  