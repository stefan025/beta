<?php
$data = json_decode(file_get_contents("../data/events.json"), true) ?: [];

$total = count($data);

$events = [];
$sources = [];

foreach ($data as $e) {
  $type = $e["event"] ?? "unknown";
  $events[$type] = ($events[$type] ?? 0) + 1;

  $ref = $e["ref"] ?? "direct";
  $sources[$ref] = ($sources[$ref] ?? 0) + 1;
}

$leads = $events["lead"] ?? 0;
$contacts = $events["contact_click"] ?? 0;
$ctr = $total ? round(($contacts / $total) * 100, 2) : 0;
$conv = $total ? round(($leads / $total) * 100, 2) : 0;
?>

<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<title>Ads Performance Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
  margin:0;
  font-family: Inter;
  background:#050814;
  color:#e5e7eb;
}

/* HEADER */
.header {
  padding:30px 50px;
}

.header h1 {
  font-size:26px;
  font-weight:600;
}

.header p {
  color:#94a3b8;
  font-size:13px;
}

/* KPI GRID */
.kpi {
  display:grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap:15px;
  padding:0 50px;
}

.card {
  background: linear-gradient(145deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
  border:1px solid rgba(255,255,255,0.08);
  border-radius:18px;
  padding:18px;
  backdrop-filter: blur(10px);
}

.card h3 {
  font-size:12px;
  color:#94a3b8;
}

.card h1 {
  font-size:24px;
  margin-top:8px;
}

/* FUNNEL */
.funnel {
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap:15px;
  padding:20px 50px;
}

.step {
  background:#0b1020;
  border:1px solid rgba(255,255,255,0.08);
  border-radius:16px;
  padding:18px;
}

.step h3 {
  font-size:12px;
  color:#94a3b8;
}

.step h1 {
  margin-top:10px;
}

/* CHARTS */
.grid {
  display:grid;
  grid-template-columns: 2fr 1fr;
  gap:15px;
  padding:20px 50px;
}

.panel {
  background:#0b1020;
  border:1px solid rgba(255,255,255,0.08);
  border-radius:16px;
  padding:18px;
}

/* TABLE */
.table {
  padding:20px 50px 50px;
}

table {
  width:100%;
  border-collapse: collapse;
  background:#0b1020;
  border:1px solid rgba(255,255,255,0.08);
  border-radius:16px;
  overflow:hidden;
}

th, td {
  padding:12px;
  font-size:13px;
  border-bottom:1px solid rgba(255,255,255,0.06);
}

th {
  color:#94a3b8;
  text-align:left;
}

@media (max-width: 768px) {

  .header {
    padding:20px;
  }

  .kpi {
    grid-template-columns: repeat(2, 1fr);
    padding: 0 20px;
  }

  .funnel {
    grid-template-columns: 1fr;
    padding: 20px;
  }

  .grid {
    grid-template-columns: 1fr;
    padding: 20px;
  }

  .table {
    padding: 20px;
    overflow-x: auto;
  }

  table {
    min-width: 600px;
  }
}
</style>

</head>

<body>

<div class="header">
  <h1>Ads Performance Dashboard</h1>
  <p>Optimizare campanii HVAC — trafic, lead-uri și conversii</p>
</div>

<!-- KPI -->
<div class="kpi">

  <div class="card">
    <h3>Total Events</h3>
    <h1><?= $total ?></h1>
  </div>

  <div class="card">
    <h3>Contact Clicks</h3>
    <h1><?= $contacts ?></h1>
  </div>

  <div class="card">
    <h3>Leads</h3>
    <h1><?= $leads ?></h1>
  </div>

  <div class="card">
    <h3>CTR (Contact Rate)</h3>
    <h1><?= $ctr ?>%</h1>
  </div>

</div>

<!-- FUNNEL -->
<div class="funnel">

  <div class="step">
    <h3>1. Traffic</h3>
    <h1><?= $total ?></h1>
  </div>

  <div class="step">
    <h3>2. Engagement</h3>
    <h1><?= $contacts ?></h1>
  </div>

  <div class="step">
    <h3>3. Leads</h3>
    <h1><?= $leads ?></h1>
  </div>

</div>

<!-- CHARTS -->
<div class="grid">

  <div class="panel">
    <h3>Event Breakdown</h3>
    <canvas id="c1"></canvas>
  </div>

  <div class="panel">
    <h3>Traffic Sources</h3>
    <canvas id="c2"></canvas>
  </div>

</div>

<!-- TABLE -->
<div class="table">

<table>
<tr>
  <th>Event</th>
  <th>Page</th>
  <th>Time</th>
</tr>

<?php foreach (array_slice(array_reverse($data), 0, 10) as $e): ?>
<tr>
  <td><?= $e["event"] ?></td>
  <td><?= $e["url"] ?></td>
  <td><?= date("H:i d/m", ($e["time"] ?? time()*1000)/1000) ?></td>
</tr>
<?php endforeach; ?>

</table>

</div>

<script>
const events = <?= json_encode($events) ?>;
const sources = <?= json_encode($sources) ?>;

new Chart(document.getElementById("c1"), {
  type:"doughnut",
  data:{
    labels:Object.keys(events),
    datasets:[{ data:Object.values(events) }]
  }
});

new Chart(document.getElementById("c2"), {
  type:"bar",
  data:{
    labels:Object.keys(sources),
    datasets:[{ data:Object.values(sources) }]
  },
  options:{
    plugins:{ legend:{ display:false } }
  }
});
</script>

</body>
</html>