<?php
require '../../config/Database.php';
require '../../config/Functions.php';
require '../../vendor/autoload.php';

$database = new Database();
$functions = new Functions();
$error = null;
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$foundAccommodation = $database->getvalues('accommodations','WHERE id = :id',["id" => $id]);
$error = $functions->validateAccommodationDelete($foundAccommodation);
if($error == null) {
    $database->del('accommodations','WHERE id = :id', ["id" => $id]);
}
$result = [
    'data' => null,
    'error' => $error,
    'message' => $error == null ? 'Successfully deleted' : 'An error occured'
];
echo json_encode($result);

exit();