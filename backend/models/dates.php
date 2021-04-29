<?php 

class Dates {

    public $date_id;
    public $date;
    public $appointment_id;


    function __construct($date_id, $date, $appointment_id)
    {
        $this->date_id = $date_id;
        $this->date = NULL;
        $this->appointment_id = NULL;

        $this->setValue($date, "date");
        $this->setValue($appointment_id, "appointment_id");
        
    }

    function setValue($value, $valueToSet)
    {
        $err = NULL;
        if (empty($value)) {
            $err = "Bitte füllen Sie alle Felder aus";
        } else {
            $value = $this->checkInput($value);

            if (!preg_match('/^[a-z0-9 .\-]+$/i', $value)) {
                $err = "Bitte geben Sie nur Buchstaben oder Zahlen ein";
            } else if ($this->sizeCheck($value) != true) {
                $err = "Die eingegebenen Daten sind zu lang";
            } else {
                $this->$valueToSet = $value;
            }
        }
        return $err;
    }

    private function checkInput($input)
    {
        return htmlspecialchars(stripslashes(trim($input)));
    }

    private function sizeCheck($input)
    {
        return strlen($input) < 255;
    }


}


?>