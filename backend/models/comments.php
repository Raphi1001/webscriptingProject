<?php 

class Comments{

    public $comment_id;
    public $from_id;
    public $appointment_id;
    public $comment;


    function __construct($comment_id, $from_id, $appointment_id, $comment)
    {
        $this->comment_id = $comment_id;
        $this->from_id = $from_id;
        $this->appointment_id = $appointment_id;
        $this->comment = $comment;
    }

}

?>