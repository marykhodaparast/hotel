<?php
require '../../config/Database.php';
require '../../config/Functions.php';
require '../../vendor/autoload.php';

$database = new Database();
$functions = new Functions();
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$values = $database->getvalues('locations','WHERE is_deleted = 0', [], true);
$result = [
    'data' => $values,
    'error' => null,
    'message' => null
];
echo(json_encode($result));
exit();