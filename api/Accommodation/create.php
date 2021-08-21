<?php
require '../../config/Database.php';
require '../../config/Functions.php';
require '../../vendor/autoload.php';

use Carbon\Carbon;

$database = new Database();
$functions = new Functions();
$error = null;
$data = null;
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_STRING);
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
$locations_id = filter_input(INPUT_POST, 'locations_id', FILTER_SANITIZE_STRING);
$reputation = filter_input(INPUT_POST, 'reputation', FILTER_SANITIZE_STRING);
$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING);
$availability = filter_input(INPUT_POST, 'availability', FILTER_SANITIZE_STRING);
$reputationBadge = null;
$file = $_FILES['image'];
$pos = substr($file['name'], 0, strpos($file['name'], '.'));
$filename = $pos . '_' . time();
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
if ($reputation <= 500) {
    $reputationBadge = "red";
} else if ($reputation > 500 && $reputation <= 799) {
    $reputationBadge = "yellow";
} else {
    $reputationBadge = "green";
}
$now = Carbon::now()->format('Y-m-d H:i:s');
$error = $functions->validateAccommodationCreate($locations_id, $name, $category, $rating, $reputation, $price, $availability, $file['size']);
if (move_uploaded_file($file['tmp_name'], "../../images/$filename.$ext") && $error == null) {
    $data = [
        'name' => $name,
        'rating' => $rating,
        'category' => $category,
        'locations_id' => $locations_id,
        'image' => "localhost/php-test/images/$filename.$ext",
        'reputation' => $reputation,
        'reputationBadge' => $reputationBadge,
        'price' => $price,
        'availability' => $availability,
        'created_at' => $now,
        'updated_at' => $now
    ];
    $database->insert($data, $database->getPdo(), 'accommodations');
}
$foundLocation = $database->getvalues('locations','WHERE id = :id and is_deleted = 0',["id" => $locations_id]);
$values = [
    'name' => $name,
    'rating' => $rating,
    'category' => $category,
    'location' => $foundLocation,
    'image' => "localhost/php-test/images/$filename.$ext",
    'reputation' => $reputation,
    'reputationBadge' => $reputationBadge,
    'price' => $price,
    'availability' => $availability
];
$result = [
    'data' => $error == null ? $values : null,
    'error' => $error,
    'message' => $error == null ? 'Successfully created' : 'An error occured'
];
echo json_encode($result);

exit();
