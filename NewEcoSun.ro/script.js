function calculateInstallation(){

  let total = 1200;
  let details = [];

  // PERETE
  const wall = parseInt(document.getElementById('wallType').value);
  total += wall;

  if(wall === 150) details.push("Gaura beton");
  if(wall === 100) details.push("Gaura cărămidă");

  // CONDUCTA
  const pipe = parseInt(document.getElementById('pipeMeters').value) || 0;
  total += pipe * 150;
  if(pipe > 0) details.push(pipe + "m conductă");

  // CABLU
  const cable = parseInt(document.getElementById('cableMeters').value) || 0;
  total += cable * 150;
  if(cable > 0) details.push(cable + "m cablu");

  // SCARĂ
  if(document.getElementById('ladderInstall').checked){
    total += 100;
    details.push("Montaj pe scară");
  }

  // FREON
  const freon = parseInt(document.getElementById('freon').value) || 0;
  total += (freon / 100) * 150;
  if(freon > 0) details.push(freon + "gr freon");

  // IGIENIZARE
  if(document.getElementById('cleaning').checked){
    total += 250;
    details.push("Igienizare AC");
  }

  // ABONAMENT
  if(document.getElementById('subscription').checked){
    total += 5000;
    details.push("Abonament anual");
  }

  // OUTPUT
  document.getElementById('resultPrice').innerText = total + " LEI";

  document.getElementById('resultDetails').innerHTML =
    details.length ? details.join("<br>") : "Montaj standard inclus";
}