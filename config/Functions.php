<?php
class Functions{

    protected $database;
    public function __construct()
    {
        $this->database = new Database();
    }
    public function validateZipCode($zip_code, $method = "create", $item = true)
    {
        $zip_code_length = strlen((string)$zip_code);
        $error = null;
        if($zip_code != null) {
            if(!is_numeric($zip_code)){
                $error = "zip_code should contain numbers";
            }
            if($zip_code_length != 5) {
                $error = "zip_code must contain 5 characters";
            }
            if(is_numeric($zip_code) && $zip_code <= 0) {
                $error = "zip_code can not be negative number";
            }
        }
       
        if($method == "update" && $item) {
            $error = "The location with the id not found!";
        }
        return $error;
    }
    public function validateLocationDelete($item)
    {
        $error = null;
        if($item) {
            $error = "The location with the id not found!";
        }
        return $error;

    }
    public function validateAccommodationDelete($item) 
    {
        $error = null;
        if(!$item) {
            $error = "The accommodation with the id not found!";
        }
        return $error; 
    }
    public function validateAccommodationCreate($locations_id, $name, $category, $rating, $reputatation, $price, $availability, $file_size)
    {

        $found = $this->database->getvalues('locations','WHERE id = :id and is_deleted = 0',[":id" => $locations_id]);
        $error = null;
        if(!$found) {
            $error = "The locations_id is not valid";
        }
        $category_array = ['hotel', 'alternative', 'hostel', 'lodge', 'resort', 'guest-house'];
        if(!in_array($category, $category_array)) {
            $error = "The category is not valid";
        }
        if(is_numeric($category)) {
            $error = "The category should not be a number";
        }
        if(strlen($name) <= 10) {
            $error = "The name should have more than 10 characters";
        }
        if(str_contains($name, 'Free') || str_contains($name, 'Offer') || str_contains($name, 'Book') || str_contains($name, 'Website')) {
            $error = "The name should not contain the words(Free, Offer, Book, Website)";
        }
        if(!is_numeric($reputatation)){
            $error = "The reputatation should be an integer";
        }
        if(!is_numeric($rating)){
            $error = "The rating should be an integer";
        }
        if($rating < 0 && $rating > 5) {
            $error = "The rating should be between 0 & 5";
        }
        if($reputatation < 0 || $reputatation > 1000) {
            $error = "The reputation should be between 0 & 1000";
        }
        
        if(!is_numeric($price)){
            $error = "The price should be an integer";
        }
        if(!is_numeric($availability)){
            $error = "The availability should be an integer";
        }
        if ($file_size > 2000000) {
            $error = "File is too big";
        }
        return $error;
    }
}
