<?php
include_once "models/appointment.php";
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
        $result = array();

        foreach ($this->queryAppointment() as $val) {
            if ($val->app_id == $id) {
                array_push($result, $val);
            }
        }
        return $result;
    }

    private static function getDemoData()
    {
        $demodata = [
            new Appointment(1, "Appointment Cool", "Wien", "Super coole beschreibung", "Morgen", 1),
            new Appointment(2, "Cooles Appointment", "Burgenland", "das ist die description", "Heute", 5354354),
            new Appointment(3, "Auch spannenvvvvvvvvvd", "Niederösterreich", "und so weiter", "Nächste woche", 77),
            new Appointment(4, "Test", "Afrika", "mir fällt nichts mehr ein", "nie", 6),
            new Appointment(5, "Test", "Afrika", "mir fällt nichts mehr einsaddasdaddddddddddddddddddddddddddddddddddddddddddddd", "nie", 6),

            new Appointment(6, "Test", "Afrika", "mir fällt nichts mehrasdd ein", "nie", 6),

            new Appointment(7, "Test", "Afrika", "mir fällt nichts xcxxmehr ein", "nie", 6),

        ];
        return $demodata;
    }



    public function queryComment()
    {
        $res =  $this->getDemoCommentData();
        return $res;
    }

    public function queryCommentByAppId($appId)
    {
        $result = array();
        foreach ($this->queryComment() as $val) {
            if ($val->appointment_id == $appId) {
                array_push($result, $val);
            }
        }
        return $result;
    }

    private static function getDemoCommentData()
    {
        $demodata = [
            new Comments(1, "harry", 1, "Ein wichtiger kommentar"),
            new Comments(2, "Tribun", 1, "I <3 Webscripting"),
            new Comments(3, "Blass", 4, "hoch interessant"),
            new Comments(4, "wir", 4, "Lückenfüller"),
            new Comments(5, "ich", 4, "Gönnjamin"),
            new Comments(6, "du", 3, "ich finde das nice"),
        ];
        return $demodata;
    }
}



/*
class DataHandler extends DB {
    
    public $conn = new DB("localhost", "bif2webscriptinguser", "bif2021", "webscript_project");


    function getAppList() {
        $this->conn->getAppointmentList();
    }

}


?>

*/