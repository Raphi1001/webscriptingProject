<?php
include_once "models/appointment.php";
include_once "models/comments.php";
include_once "db/db.php";
class DataHandler
{
    public $conn;


    public function __construct()
    {
        $this->conn = new DB("localhost", "bif2webscriptinguser", "bif2021", "webscript_project");
    }

    public function queryAppointment()
    {
        $res =  $this->conn->getAppointmentList();
        return $res;
    }

    public function queryAppointmentById($id)
    {
        $res =  $this->conn->getAppointment($id);
        return $res;
    }

    public function insertAppointment($newAppointmentDetails)
    {
        $newApp = new Appointment(0, $newAppointmentDetails[0], $newAppointmentDetails[1], $newAppointmentDetails[2], $newAppointmentDetails[3], $newAppointmentDetails[4]);
        if (
            !$newApp->title ||
            !$newApp->location ||
            !$newApp->description ||
            !$newApp->vote_expire ||
            !$newApp->creator_name
        )   return false;

        $res =  $this->conn->createAppointment($newApp);
        return $res;
    }



    public function queryCommentByAppId($appId)
    {
        $res =  $this->conn->getCommentListByAppId($appId);
        return $res;
    }
    public function insertComment($newCommentDetails)
    {
        $newComment = new Comments(0, $newCommentDetails[0], $newCommentDetails[1], $newCommentDetails[2]);
        if (
            !$newComment->creator_name ||
            !$newComment->appointment_id ||
            !$newComment->comment
        )   return false;

        $res =  $this->conn->createComment($newComment);
        return $res;
    }
}