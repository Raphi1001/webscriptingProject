<?php 

class Comments{

    public $comment_id;
    public $creator_name;
    public $appointment_id;
    public $comment;


    function __construct($comment_id, $creator_name, $appointment_id, $comment)
    {
        $this->comment_id = $comment_id;
        $this->creator_name = NULL;
        $this->appointment_id = NULL;
        $this->comment = NULL;

        $this->setValue($comment_id, "comment_id");
        $this->setValue($creator_name, "creator_name");
        $this->setValue($appointment_id, "appointment_id");
        $this->setValue($comment, "comment");
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
