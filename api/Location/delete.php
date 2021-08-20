<?php
require '../../config/Database.php';
require '../../config/Functions.php';
require '../../vendor/autoload.php';

$database = new Database();
$functions = new Functions();
$error = null;
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$foundLocation = $database->getvalues('locations','WHERE id = :id',["id" => $id]);
$error = $functions->validateLocationDelete(isset($foundLocation->scalar));
if($error == null) {
    $database->del('locations','WHERE id = :id', ["id" => $id]);
}
$result = [
    'data' => null,
    'error' => $error,
    'message' => $error == null ? 'Successfully deleted' : 'An error occured'
];
echo json_encode($result);

exit();