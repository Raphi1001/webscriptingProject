<?php

class Appointment {

    public $app_id;
    public $title;
    public $location;
    public $description;
    public $vote_expire;
    public $creator_name;


    function __construct($app_id, $title, $location, $description, $vote_expire, $creator_name)
    {
        $this->app_id = $app_id;
        $this->title = $title;
        $this->location = $location;
        $this->description = $description;
        $this->vote_expire = $vote_expire;
        $this->creator_name = $creator_name;
    }



}

?>