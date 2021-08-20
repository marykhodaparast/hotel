<?php
class Functions{

    public function validateZipCode($zip_code, $method = "create", $item = true)
    {
        $zip_code_length = strlen((string)$zip_code);
        $error = null;
        if($zip_code != null) {
            if(!is_numeric($zip_code) && $zip_code > 0){
                $error = "zip_code should contain numbers";
            }
            if($zip_code_length != 5) {
                $error = "zip_code must contain 5 characters";
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
}
