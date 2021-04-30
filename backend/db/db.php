<?php
class DB
{
    protected $host;
    protected $username;
    protected $passwd;
    protected $dbname;
    protected $db;

    public function __construct($host, $username, $passwd, $dbname)
    {
        $this->host = $host;
        $this->username = $username;
        $this->passwd = $passwd;
        $this->dbname = $dbname;
        $this->db = new mysqli();
        $this->connect();
    }

    /**
     * Returns property if name exists
     * 
     * @param string $propName Name of the property
     * @return mixed Value of the property, NULL if not exists
     */
    public function getProperty($propName)
    {
        if (property_exists($this, $propName)) {
            return $this->$propName;
        }
        return NULL;
    }

    /**
     * Open a database connection 
     */
    public function connect()
    {
        $this->db->connect($this->host, $this->username, $this->passwd, $this->dbname);
    }

    /**
     * Closes a database connection much wow
     */
    public function disconnect()
    {
        $this->db->close();
    }


    /**
     * Returns a list of all Appointments as an array of Appointment objects
     * 
     * @return array Array of Appointment objects
     */
    public function getAppointmentList()
    {
        if ($this->db->errno != 0) return false;

        $appointmentArray = array();
        $result = $this->db->query("SELECT * FROM appointments");
        if (!$result || !$result->num_rows) {
            $result->free_result();
            return false;
        }
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($appointmentArray, new Appointment($row["app_id"], $row["title"], $row["location"], $row["description"], $row["vote_expire"], $row["creator_name"]));
        }
        $result->free_result();
        return $appointmentArray;
    }


    //returns the appointment object with the corresponding appointmentID
    public function getAppointment($app_id)
    {
        if ($this->db->errno != 0) return false;

        $appointmentArray = array();

        $result = $this->db->query("SELECT * FROM appointments WHERE app_id = $app_id");
        if (!$result || !$result->num_rows) {
            $result->free_result();
            return false;
        }
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($appointmentArray, new Appointment($row["app_id"], $row["title"], $row["location"], $row["description"], $row["vote_expire"], $row["creator_name"]));
        }
        $result->free_result();
        return $appointmentArray;
    }


    //returns list of comments from a certain appointment
    public function getCommentListByAppId($app_id)
    {
        if ($this->db->errno != 0) return false;

        $appointmentArray = array();
        $result = $this->db->query("SELECT * FROM comments WHERE appointment_id = $app_id ORDER BY comment_id DESC");
        if (!$result || !$result->num_rows) {
            $result->free_result();
            return false;
        }
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($appointmentArray, new Comments($row["comment_id"], $row["creator_name"], $row["appointment_id"], $row["comment"]));
        }
        $result->free_result();
        return $appointmentArray;
    }



    //returns list of dates in a certain appointment
    public function getDatesByAppId($app_id)
    {
        if ($this->db->errno != 0) return false;

        $datesArray = array();
        $result = $this->db->query("SELECT * FROM dates WHERE appointment_id = $app_id");
        if (!$result || !$result->num_rows) {
            $result->free_result();
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            array_push($datesArray, new Dates($row["date_id"], $row["date"], $row["appointment_id"]));
        }
        $result->free_result();
        return $datesArray;
    }


    //returns list of votes with a certain date
    public function getVotesByDateId($date_id)
    {
        if ($this->db->errno != 0) return false;

        $datesArray = array();
        $result = $this->db->query("SELECT * FROM votes WHERE date_id = $date_id");
        if (!$result || !$result->num_rows) {
            $result->free_result();
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            array_push($datesArray, new Votes($row["vote_id"], $row["vote_name"], $row["date_id"]));
        }
        $result->free_result();
        return $datesArray;
    }

    //reutns the number of votes on a certain date
    public function countVotesByDateId($date_id)
    {
        if ($this->db->errno != 0) return false;

        $votes = array();
        $result = $this->db->query("SELECT * FROM votes WHERE date_id = $date_id;");
        if (!$result || !$result->num_rows) {
            $result->free_result();
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            array_push($votes, new Votes($row["vote_id"], $row["vote_name"], $row["date_id"]));
        }
        $result->free_result();
        return $votes;
    }

    //returns number of votes depending on name and appointment
    public function checkVotesOnName($app_id, $vote_name)
    {
        if ($this->db->errno != 0) return false;

        $votes = 0;
        $result = $this->db->query("SELECT COUNT(*) FROM votes v JOIN dates d ON d.date_id=v.date_id WHERE vote_name = '$vote_name' AND appointment_id = $app_id;");
        if (!$result || !$result->num_rows) {
            $result->free_result();
            return false;
        }
        $votes = $result->fetch_assoc();
        $result->free_result();
        return $votes['COUNT(*)'];
    }


    //returns a list of votes depending on the name and apointment
    public function getVotesByName($vote_name, $app_id)
    {
        if ($this->db->errno != 0) return false;

        $votesArray = array();
        $result = $this->db->query("SELECT * FROM votes v JOIN dates d ON d.date_id=v.date_id WHERE vote_name = '$vote_name' AND appointment_id = $app_id;");
        if (!$result || !$result->num_rows) {
            $result->free_result();
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            array_push($votesArray, new Votes($row["vote_id"], $row["vote_name"], $row["date_id"]));
        }
        $result->free_result();
        return $votesArray;
    }


    //returns a list of names that have voted under a certain appointment
    public function getNamesVotedByAppointment($app_id)
    {
        if ($this->db->errno != 0) return false;

        $namesArray = array();
        $result = $this->db->query("SELECT DISTINCT vote_name FROM votes v JOIN dates d ON d.date_id=v.date_id WHERE appointment_id = $app_id;");
        if (!$result || !$result->num_rows) {
            $result->free_result();
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            array_push($namesArray, $row["vote_name"]);
        }
        $result->free_result();
        return $namesArray;
    }


    //returns the highest app_id that exists in the database
    public function getHighestAppId()
    {
        if ($this->db->errno != 0) return false;

        $highestId = 0;
        $result = $this->db->query("SELECT MAX(app_id) FROM appointments;");
        if (!$result || !$result->num_rows) {
            $result->free_result();
            return false;
        }
        $row = $result->fetch_assoc();
    
        $highestId = $row["MAX(app_id)"];

        $result->free_result();
        return $highestId;
    }


    //all delete-functions are used, when the "delete"-button is pressed

    public function deleteAppointment($app_id)
    {
        if ($this->db->errno != 0) return false;

        $stmt = $this->db->prepare("DELETE FROM appointments WHERE app_id = ?;");
        if (!$stmt) return false;

        $stmt->bind_param("i", $app_id);
        $stmt->execute();

        if ($stmt->errno != 0) return false;

        return true;
    }

    public function deleteComment($app_id)
    {
        if ($this->db->errno != 0) return false;

        $stmt = $this->db->prepare("DELETE FROM comments WHERE appointment_id = ?;");
        if (!$stmt) return false;

        $stmt->bind_param("i", $app_id);
        $stmt->execute();

        if ($stmt->errno != 0) return false;

        return true;
    }

    public function deleteDate($app_id)
    {
        if ($this->db->errno != 0) return false;

        $stmt = $this->db->prepare("DELETE FROM dates WHERE appointment_id = ?;");
        if (!$stmt) return false;

        $stmt->bind_param("i", $app_id);
        $stmt->execute();

        if ($stmt->errno != 0) return false;

        return true;
    }

    public function deleteVotes($app_id)
    {
        if ($this->db->errno != 0) return false;

        $stmt = $this->db->prepare("DELETE v FROM votes v JOIN dates d ON v.date_id = d.date_id WHERE appointment_id = ?;");
        if (!$stmt) return false;

        $stmt->bind_param("i", $app_id);
        $stmt->execute();

        if ($stmt->errno != 0) return false;

        return true;
    }


    





    //create-Funktionen zum erstellen der Dateneinträge

    public function createAppointment($newApp)
    {
        if ($this->db->errno != 0) return false;

        $stmt = $this->db->prepare("INSERT INTO appointments(title, location, description, vote_expire, creator_name) VALUES (?,?,?,?,?)");
        if (!$stmt) return false;

        $stmt->bind_param("sssss", $newApp->title, $newApp->location, $newApp->description, $newApp->vote_expire, $newApp->creator_name);
        $stmt->execute();
        if ($stmt->errno != 0) return false;

        return true;
    }

    public function createComment($newComment)
    {
        if ($this->db->errno != 0) return false;

        $stmt = $this->db->prepare("INSERT INTO comments(creator_name, appointment_id, comment) VALUES (?,?,?)");
        if (!$stmt) return false;

        $stmt->bind_param("sis", $newComment->creator_name, $newComment->appointment_id, $newComment->comment);
        $stmt->execute();
        if ($stmt->errno != 0) return false;

        return true;
    }


    public function createVote($newVote)
    {
        if ($this->db->errno != 0) return false;

        $stmt = $this->db->prepare("INSERT INTO votes(vote_name, date_id) VALUES (?,?)");
        if (!$stmt) return false;

        $stmt->bind_param("si", $newVote->vote_name, $newVote->date_id);
        $stmt->execute();
        if ($stmt->errno != 0) return false;

        return true;
    }


    public function createDate($newDate)
    {
        if ($this->db->errno != 0) return false;

        $stmt = $this->db->prepare("INSERT INTO dates(date, appointment_id) VALUES (?,?)");
        if (!$stmt) return false;

        $stmt->bind_param("si", $newDate->date, $newDate->appointment_id);
        $stmt->execute();
        if ($stmt->errno != 0) return false;

        return true;
    }


    
}
