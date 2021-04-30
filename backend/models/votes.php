<?php

class Votes {

    public $vote_id;
    public $vote_name;
    public $date_id;

    /*
    function __construct($vote_id, $vote_name, $date_id)
    {
        $this->vote_id = $vote_id;
        $this->vote_name = $vote_name;
        $this->date_id = $date_id;
    }
    */

    
    function __construct($vote_id, $vote_name, $date_id)
    {
        $this->vote_id = $vote_id;
        $this->vote_name = NULL;
        $this->date_id = NULL;

        $this->setValue($vote_name, "vote_name");
        $this->setValue($date_id, "date_id");
    }
    
    //checks input and sets value
    function setValue($value, $valueToSet)
    {
        $err = NULL;
        if (empty($value)) {
            $err = "Bitte füllen Sie alle Felder aus";
        } else {
            $value = $this->checkInput($value);

            if (!preg_match('/^[a-z0-9.\-]+$/i', $value)) {
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