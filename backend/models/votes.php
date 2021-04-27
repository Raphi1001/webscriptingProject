<?php

class Votes {

    public $vote_id;
    public $from_id;
    public $date_id;


    function __construct($vote_id, $from_id, $date_id)
    {
        $this->vote_id = $vote_id;
        $this->from_id = $from_id;
        $this->date_id = $date_id;
    }

}

?>