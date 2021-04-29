<?php
include("db/dataHandler.php");

class SimpleLogic
{
    private $dh;
    function __construct()
    {
        $this->dh = new DataHandler();
    }

    function handleRequest($method, $param)
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
            default:
                $res = null;
                break;
        }
        return $res;
    }
}
