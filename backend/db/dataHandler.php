<?php
include_once "models/appointment.php";
include_once "models/comments.php";
include_once "models/dates.php";
include_once "models/votes.php";
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
        if($res) $res = $this->insertDates($newAppointmentDetails[5]);
        return $res;
    }


    public function getHighestAppId()
    {
        $res = $this->conn->getHighestAppId();
        return $res;
    }

    public function insertDates($appointmentDateOptionsArr)
    {
        $app_id = $this->getHighestAppId();
        foreach ($appointmentDateOptionsArr as $dateOption) {
            $newDate = new Dates(0, $dateOption, $app_id);
            if (
                !$newDate->date ||
                !$newDate->appointment_id
            )   return false;

            $res =  $this->conn->createDate($newDate);
            if (!$res) return false;
        }
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

    public function queryDatesByAppId($appId)
    {
        $res =  $this->conn->getDatesByAppId($appId);
        return $res;
    }
    public function queryVoteCountByDateId($dateId)
    {
        $res =  $this->conn->countVotesByDateId($dateId);
        return $res;
    }

    public function deleteAppointment($appId)
    {
        $res1 = $this->conn->deleteVotes($appId);
        $res2 = $this->conn->deleteDate($appId);
        $res3 = $this->conn->deleteComment($appId);
        $res4 = $this->conn->deleteAppointment($appId);
        if ($res1 != true || $res2 != true || $res3 != true || $res4 != true) {
            $res = false;
        } else {
            $res = true;
        }

        return $res;
    }

    public function queryAppointmentVotes($appId)
    {
        $res =  $this->conn->getNamesVotedByAppointment($appId);
        return $res;
    }

    public function queryUserVotes($username, $appId)
    {
        $res =  $this->conn->getVotesByName($username, $appId);
        return $res;
    }


    public function insertVote($username, $dateId)
    {
        $newVote = new Votes(0, $username, $dateId);
        if (
            !$newVote->vote_name ||
            !$newVote->date_id
        )   return false;

        $res =  $this->conn->createVote($newVote);
        return $res;
    }




    public function queryVotesByDateId($dateId)
    {
        $res =  $this->conn->getVotesByDateId($dateId);
        return $res;
    }
}
