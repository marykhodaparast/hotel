<?php
require '../../config/Database.php';
require '../../config/Functions.php';
require '../../vendor/autoload.php';

$database = new Database();
$functions = new Functions();
$error = null;
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$foundAccommodation = $database->getvalues('accommodations','WHERE id = :id and is_deleted = 0',["id" => $id]);
$error = $functions->validateAccommodationDelete(isset($foundAccommodation));
$result = [
    'data' => $foundAccommodation ? $foundAccommodation : null,
    'error' => $error,
    'message' => null
];
echo json_encode($result);

exit();