<?php

if (!isset($_FILES['video'])) {

    die("No se recibió ningún archivo");

}

$apiUrl = "https://litterbox.catbox.moe/resources/internals/api.php";

// 1️⃣ Subir video a Litterbox

$postData = [

    'reqtype' => 'fileupload',

    'time' => '24h',

    'fileToUpload' => new CURLFile($_FILES['video']['tmp_name'])

];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $apiUrl);

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$videoUrl = trim(curl_exec($ch));

curl_close($ch);

if (!filter_var($videoUrl, FILTER_VALIDATE_URL)) {

    die("Error al subir el video");

}

// 2️⃣ Generar título por fecha

$title = "Video " . date("Y-m-d H-i-s");

// 3️⃣ Miniatura fija

$thumbnail = "https://tusitio.infinityfreeapp.com/thumb.jpg";

// 4️⃣ Nuevo registro

$newItem = [

    "id" => $videoUrl,

    "ic" => $thumbnail,

    "title" => $title,

    "premium" => false

];

// 5️⃣ Guardar en JSON

$jsonFile = "videos.json";

if (file_exists($jsonFile)) {

    $data = json_decode(file_get_contents($jsonFile), true);

    if (!is_array($data)) $data = [];

} else {

    $data = [];

}

$data[] = $newItem;

file_put_contents(

    $jsonFile,

    json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)

);

echo "OK";