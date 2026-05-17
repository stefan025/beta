<?php
$data = json_decode(file_get_contents("php://input"), true);

$old = $data["file"];
$new = dirname($old)."/".$data["newName"];

rename($old,$new);
echo "renamed";