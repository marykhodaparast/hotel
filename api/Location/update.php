<?php
require '../../config/Database.php';
require '../../config/Functions.php';
require '../../vendor/autoload.php';
use Carbon\Carbon;

$database = new Database();
$functions = new Functions();
$error = null;
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
$state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
$zip_code = filter_input(INPUT_POST, 'zip_code', FILTER_SANITIZE_STRING);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
$now = Carbon::now()->format('Y-m-d H:i:s');
$foundLocation = $database->getvalues('locations','WHERE id = :id and is_deleted = 0',["id" => $id]);
$error = $functions->validateZipCode($zip_code,'update', !$foundLocation );
if($error == null) {
    $values = [
        "city" => $city == null ? $foundLocation->city : $city,
        "state" => $state == null ? $foundLocation->state : $state,
        "country" => $country == null ? $foundLocation->country : $country,
        "zip_code" => $zip_code == null ? $foundLocation->zip_code : $zip_code,
        "address" => $address == null ? $foundLocation->address : $address,
        "created_at" => $foundLocation->created_at,
        "updated_at" => $now
    ];
    $database->update('locations', $id, $values);
}
$result = [
    'data' => $error == null ? $values : null,
    'error' => $error,
    'message' => $error == null ? 'Successfully updated' : 'An error occured'
];
echo json_encode($result);

exit();