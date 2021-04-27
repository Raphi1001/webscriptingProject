<?php 

class Participation {

    public $participation_id;
    public $user_id;
    public $appointment_id;


    function __construct($participation_id, $user_id, $appointment_id)
    {
        $this->participation_id = $participation_id;
        $this->user_id = $user_id;
        $this->appointment_id = $appointment_id;
    }

}

?>