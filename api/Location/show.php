<?php
require '../../config/Database.php';
require '../../config/Functions.php';
require '../../vendor/autoload.php';

$database = new Database();
$functions = new Functions();
$error = null;
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$foundLocation = $database->getvalues('locations','WHERE id = :id and is_deleted = 0',["id" => $id]);
$error = $functions->validateLocationDelete(isset($foundLocation->scalar));
$result = [
    'data' => $error == null ? $foundLocation : null,
    'error' => $error,
    'message' => null
];
echo json_encode($result);

exit();