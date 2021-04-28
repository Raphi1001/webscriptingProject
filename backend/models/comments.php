<?php 

class Comments{

    public $comment_id;
    public $creator_name;
    public $appointment_id;
    public $comment;


    function __construct($comment_id, $creator_name, $appointment_id, $comment)
    {
        $this->comment_id = $comment_id;
        $this->from_id = $creator_name;
        $this->appointment_id = $appointment_id;
        $this->comment = $comment;
    }

}

?>