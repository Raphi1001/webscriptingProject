<?php
include("./models/appointment.php");
class DataHandler
{
    public function queryAppointment()
    {
        $res =  $this->getDemoData();
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

    public function queryAppointmentByTitle($title)
    {
        $result = array();
        foreach ($this->queryAppointment() as $val) {
            if ($val->title == $title) {
                array_push($result, $val);
            }
        }
        return $result;
    }

    private static function getDemoData()
    {

        $demodata = [
            new Appointment(1, "Appointment Cool", "Wien", "Super coole beschreibung", "5-5-5", "Morgen", 1),
            new Appointment(2, "Cooles Appointment", "Burgenland", "das ist die description", "6-6-6", "Heute", 5354354),
            new Appointment(3, "Auch spannend", "Niederösterreich", "und so weiter", "7-7-7", "Nächste woche", 77),
            new Appointment(4, "Test", "Afrika", "mir fällt nichts mehr ein", "8-8-8", "nie", 6),
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