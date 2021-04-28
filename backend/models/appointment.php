<?php

class Appointment {

    public $app_id;
    public $title;
    public $location;
    public $description;
    public $vote_expire;
    public $date;
    public $creator_id;


    function __construct($app_id, $title, $location, $description, $vote_expire, $date, $creator_id)
    {
        $this->app_id = $app_id;
        $this->title = $title;
        $this->location = $location;
        $this->description = $description;
        $this->vote_expire = $vote_expire;
        $this->date = $date;
        $this->creator_id = $creator_id;
    }



}

?>