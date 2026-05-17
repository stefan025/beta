const totalElement = document.getElementById("total");
const summaryItems = document.getElementById("summary-items");

if (!totalElement || !summaryItems) {
  console.warn("Calculator elements not found.");
} else {

  const allInputs = document.querySelectorAll("input");
  const addonCards = document.querySelectorAll(".addon-card");

  function formatPrice(value){
    return value.toLocaleString("ro-RO") + " LEI";
  }

  function updateAddonStates(){

    addonCards.forEach(card => {

      const checkbox = card.querySelector("input");

      if(checkbox.checked){
        card.classList.add("active");
      } else {
        card.classList.remove("active");
      }

    });

  }

  function calculate(){

    let total = 1200;

    let summary = `
      <div class="summary-line">
        <span>Montaj standard</span>
        <strong>${formatPrice(1200)}</strong>
      </div>
    `;

    if(document.getElementById("beton").checked){
      total += 150;

      summary += `
        <div class="summary-line">
          <span>Gaură beton</span>
          <strong>${formatPrice(150)}</strong>
        </div>
      `;
    }

    if(document.getElementById("scara").checked){
      total += 100;

      summary += `
        <div class="summary-line">
          <span>Montaj pe scară</span>
          <strong>${formatPrice(100)}</strong>
        </div>
      `;
    }

    if(document.getElementById("igienizare").checked){
      total += 250;

      summary += `
        <div class="summary-line">
          <span>Igienizare AC</span>
          <strong>${formatPrice(250)}</strong>
        </div>
      `;
    }

    if(document.getElementById("abonament").checked){
      total += 5000;

      summary += `
        <div class="summary-line">
          <span>Abonament anual</span>
          <strong>${formatPrice(5000)}</strong>
        </div>
      `;
    }

    const conducta =
      parseInt(document.getElementById("conducta").value) || 0;

    if(conducta > 0){

      const cost = conducta * 150;

      total += cost;

      summary += `
        <div class="summary-line">
          <span>Conductă suplimentară (${conducta}m)</span>
          <strong>${formatPrice(cost)}</strong>
        </div>
      `;
    }

    const cablu =
      parseInt(document.getElementById("cablu").value) || 0;

    if(cablu > 0){

      const cost = cablu * 150;

      total += cost;

      summary += `
        <div class="summary-line">
          <span>Cablu suplimentar (${cablu}m)</span>
          <strong>${formatPrice(cost)}</strong>
        </div>
      `;
    }

    const freon =
      parseInt(document.getElementById("freon").value) || 0;

    if(freon > 0){

      const cost = freon * 150;

      total += cost;

      summary += `
        <div class="summary-line">
          <span>Reîncărcare freon (${freon})</span>
          <strong>${formatPrice(cost)}</strong>
        </div>
      `;
    }

    summaryItems.innerHTML = summary;
    totalElement.innerHTML = formatPrice(total);

    updateAddonStates();
  }

  allInputs.forEach(input => {

    input.addEventListener("input", calculate);
    input.addEventListener("change", calculate);

  });

  calculate();
}