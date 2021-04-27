<?php 

class Dates {

    public $date_id;
    public $date;
    public $appointment_id;


    function __construct($date_id, $date, $appointment_id)
    {
        $this->date_id = $date_id;
        $this->date = $date;
        $this->appointment_id = $appointment_id;
    }


}


?>