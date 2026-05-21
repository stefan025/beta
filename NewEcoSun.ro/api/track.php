<?php

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

// folder data
$dir = __DIR__ . "/../data";
$file = $dir . "/events.json";

// 1. creează folder dacă nu există
if (!file_exists($dir)) {
  mkdir($dir, 0777, true);
}

// 2. creează fișierul dacă nu există
if (!file_exists($file)) {
  file_put_contents($file, json_encode([]));
}

// 3. citește datele existente
$events = json_decode(file_get_contents($file), true);
if (!is_array($events)) {
  $events = [];
}

// 4. adaugă event nou
$events[] = [
  "event" => $data["event"] ?? "unknown",
  "url"   => $data["url"] ?? "",
  "ref"   => $data["ref"] ?? "",
  "time"  => $data["time"] ?? (time() * 1000)
];

// 5. salvează
file_put_contents($file, json_encode($events, JSON_PRETTY_PRINT));

echo json_encode(["status" => "ok"]);