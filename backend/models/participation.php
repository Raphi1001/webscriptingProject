<?php 

class Participation {

    public $participation_id;
    public $participator_name;
    public $appointment_id;


    function __construct($participation_id, $participator_name, $appointment_id)
    {
        $this->participation_id = $participation_id;
        $this->user_id = $participator_name;
        $this->appointment_id = $appointment_id;
    }

}

?>