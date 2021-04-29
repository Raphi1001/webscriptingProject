<?php
include("db/dataHandler.php");

class SimpleLogic
{
    private $dh;
    function __construct()
    {
        $this->dh = new DataHandler();
    }

    function handleRequest($method, $param, $param2)
    {
        switch ($method) {
            case "queryAppointment":
                $res = $this->dh->queryAppointment();
                break;
            case "queryAppointmentById":
                $res = $this->dh->queryAppointmentById($param);
                break;
            case "queryCommentByAppId":
                $res = $this->dh->queryCommentByAppId($param);
                break;
            case "insertAppointment":
                $res = $this->dh->insertAppointment($param);
                break;
            case "insertComment":
                $res = $this->dh->insertComment($param);
                break;
            case "queryDatesByAppId":
                $res = $this->dh->queryDatesByAppId($param);
                break;
            case "queryVoteCountByDateId":
                $res = $this->dh->queryVoteCountByDateId($param);
                break;

            case "queryAppointmentVotes":
                $res = $this->dh->queryAppointmentVotes($param);
                break;
            case "queryUserVotes":
                $res = $this->dh->queryUserVotes($param, $param2);
                break;
            case "insertVote":
                $res = $this->dh->insertVote($param, $param2);
                break;


            case "queryVotesByDateId":
                $res = $this->dh->queryVotesByDateId($param);
                break;
            default:
                $res = null;
                break;
        }
        return $res;
    }
}
