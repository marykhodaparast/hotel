<?php
require '../../config/Database.php';
require '../../config/Functions.php';
require '../../vendor/autoload.php';

$database = new Database();
$functions = new Functions();
$values = $database->getvalues('accommodations','WHERE is_deleted = 0', [], true);
$result = [
    'data' => $values,
    'error' => null,
    'message' => null
];
echo(json_encode($result));
exit();