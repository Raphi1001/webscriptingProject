<?php

class Appointment
{

    public $app_id;
    public $title;
    public $location;
    public $description;
    public $vote_expire;
    public $creator_name;


    function __construct($app_id, $title, $location, $description, $vote_expire, $creator_name)
    {
        $this->app_id = $app_id;
        $this->title = NULL;
        $this->location = NULL;
        $this->description = NULL;
        $this->vote_expire = NULL;
        $this->creator_name = NULL;

        $this->setValue($title, "title");
        $this->setValue($location, "location");
        $this->setValue($description, "description");
        $this->setValue($vote_expire, "vote_expire");
        $this->setValue($creator_name, "creator_name");
    }
    //checks input and sets value
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
    //remove special chars
    private function checkInput($input)
    {
        return htmlspecialchars(stripslashes(trim($input)));
    }

    //check size of input
    private function sizeCheck($input)
    {
        return strlen($input) < 255;
    }
}
