<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$file = __DIR__ . "/videos.json";

if (!file_exists($file)) {
  file_put_contents($file, json_encode([]));
}

echo file_get_contents($file);