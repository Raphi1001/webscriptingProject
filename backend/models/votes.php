<?php

class Votes {

    public $vote_id;
    public $vote_name;
    public $date_id;


    function __construct($vote_id, $vote_name, $date_id)
    {
        $this->vote_id = $vote_id;
        $this->from_id = $vote_name;
        $this->date_id = $date_id;
    }

}

?>