<?php


class User {

    public $user_id;
    public $name;


    function __construct($user_id, $name)
    {
        $this->user_id = $user_id;
        $this->name = $name;
    }

}


?>