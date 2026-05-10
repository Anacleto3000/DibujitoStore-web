<?php

$target_dir = "../imagenes/";
$nombreArchivo = basename($_FILES["imagen"]["name"]);

$rutaDestino = $target_dir . $nombreArchivo;

if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
    echo json_encode([
        "status" => "ok",
        "nombre" => $nombreArchivo
    ]);
} else {
    echo json_encode([
        "status" => "error"
    ]);
}