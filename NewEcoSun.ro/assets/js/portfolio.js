fetch('gallery.php')
  .then(res => res.json())
  .then(images => {

    const grid = document.getElementById("grid");

    images.forEach((img, index) => {

      const card = document.createElement("div");
      card.className = "portfolio-card reveal";

      card.innerHTML = `
        <img src="${img}" loading="lazy" onclick="openLB('${img}')">
        <div class="portfolio-content">
          <h3>Proiect HVAC #${index + 1}</h3>
          <p>Instalare și optimizare sistem HVAC premium pentru eficiență maximă.</p>
        </div>
      `;

      grid.appendChild(card);
    });

  });

function openLB(src) {
  const lb = document.getElementById("lightbox");
  const img = document.getElementById("lb-img");

  lb.style.display = "flex";
  img.src = src;
}

function closeLB() {
  document.getElementById("lightbox").style.display = "none";
}