<?php
require '../../config/Database.php';
require '../../config/Functions.php';
require '../../vendor/autoload.php';

use Carbon\Carbon;

$database = new Database();
$functions = new Functions();
$error = null;
$data = null;
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_STRING);
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
$locations_id = filter_input(INPUT_POST, 'locations_id', FILTER_SANITIZE_STRING);
$reputation = filter_input(INPUT_POST, 'reputation', FILTER_SANITIZE_STRING);
$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING);
$availability = filter_input(INPUT_POST, 'availability', FILTER_SANITIZE_STRING);
$reputationBadge = null;
$file = null;
$pos = null;
$filename = null;
$ext = null;
if(isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $pos = substr($file['name'], 0, strpos($file['name'], '.'));
    $filename = $pos . '_' . time();
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
}

$now = Carbon::now()->format('Y-m-d H:i:s');
$foundAccommodation = $database->getvalues('accommodations', 'WHERE id = :id and is_deleted = 0', ["id" => $id]);
if(!$foundAccommodation) {
    $error = "The accommodation not found!";
} else {
    $error = $functions->validateAccommodationCreate($locations_id, $name, $category, $rating, $reputation, $price, $availability, $file ? $file['size'] : 0);
}
if ($foundAccommodation && $error == null) {
    $r = $reputation == null ? $foundAccommodation->reputation : $reputation;
    if ($r <= 500) {
        $reputationBadge = "red";
    } else if ($r > 500 && $r <= 799) {
        $reputationBadge = "yellow";
    } else {
        $reputationBadge = "green";
    }
    if ($file != null) {
        $founded = str_replace("localhost/php-test", "", $foundAccommodation->image);
        $founded = "../..". $founded;
        if(file_exists($founded)) {
            unlink($founded);
            move_uploaded_file($file['tmp_name'], "../../images/$filename.$ext"); 
        }  
    }
    $data = [
        'name' => $name == null ? $foundAccommodation->name : $name,
        'rating' => $rating == null ? $foundAccommodation->rating : $rating,
        'category' => $category == null ? $foundAccommodation->category: $category,
        'locations_id' => $locations_id == null ? $foundAccommodation->locations_id : $locations_id,
        'image' => $file == null ? $foundAccommodation->image : "localhost/php-test/images/$filename.$ext",
        'reputation' => $r,
        'reputationBadge' => $reputationBadge,
        'price' => $price == null ? $foundAccommodation->price : $price,
        'availability' => $availability,
        'created_at' => $foundAccommodation->created_at,
        'updated_at' => $now
    ];
    $database->update('accommodations', $id, $data);
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
    'message' => $error == null ? 'Successfully updated' : 'An error occured'
];
echo json_encode($result);

exit();
