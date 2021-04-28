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
            case "queryAppointmentByTitle":
                $res = $this->dh->queryAppointmentByTitle($param);
                break;
            default:
                $res = null;
                break;
        }
        return $res;
    }
}
