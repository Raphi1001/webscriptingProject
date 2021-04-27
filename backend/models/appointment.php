<?php

class Appointment {

    public $app_id;
    public $title;
    public $location;
    public $description;
    public $date;
    public $vote_expire;
    public $creator_id;


    function __construct($app_id, $title, $location, $description, $date, $vote_expire, $creator_id)
    {
        $this->app_id = $app_id;
        $this->title = $title;
        $this->location = $location;
        $this->description = $description;
        $this->date = $date;
        $this->vote_expire = $vote_expire;
        $this->creator_id = $creator_id;
    }



}

?>