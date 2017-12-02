<?php
/**
 *
 */

class Form
{
    public $errors = [];

    public function addError($input_name, $msg)
    {
        $this->errors[] = ['input_name' => $input_name, 'message' => $msg];
    }

    public function check($key, $callback, $msg)
    {
        $value = $_POST[$key];

        if(!$callback($value))
            $this->addError($key, $msg);
    }

    public function sendResponse()
    {
        try {
            if(count($this->errors) > 0)
                throw new Exception("Error Processing Request", 1);

            echo json_encode([
                'error' => false,
                'response' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'error' => true,
                'response' => $this->errors
            ]);
        }

        die();
    }
}
