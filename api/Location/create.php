<?php
require '../../config/Database.php';
require '../../config/Functions.php';
require '../../vendor/autoload.php';
use Carbon\Carbon;

$database = new Database();
$functions = new Functions();
$error = null;
$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
$state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
$zip_code = filter_input(INPUT_POST, 'zip_code', FILTER_SANITIZE_STRING);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
$now = Carbon::now()->format('Y-m-d H:i:s');
$values = [
    "city" => $city,
    "state" => $state,
    "country" => $country,
    "zip_code" => $zip_code,
    "address" => $address,
    "created_at" => $now,
    "updated_at" => $now
];
$error = $functions->validateZipCode($zip_code,'create');
if($error == null) {
    $database->insert($values, $database->getPdo(),'locations');
}
$result = [
    'data' => $error == null ? $values : null,
    'error' => $error,
    'message' => $error == null ? 'Successfully created' : 'An error occured'
];
echo json_encode($result);

exit();