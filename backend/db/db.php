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


    /*
    //template falls wir noch eine get-query brauchen lol
    public function getDatesByAppId($app_id) 
    {
        if ($this->db->erno != 0) return false;

        $datesArray = array();
        $result = $this->db->query("SELECT * FROM dates WHERE appointment_id = $app_id;");
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
    */







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


    public function createDate($date, $app_id)
    {
        if ($this->db->errno != 0) return false;

        $query = "INSERT INTO dates('date', 'appointment_id') VALUES(?,?);";
        $stmt = $this->db->prepare($query);

        if (!$stmt) return false;

        $stmt->bind_param("si", $date, $app_id);
        $stmt->execute();

        if ($stmt->errno != 0) return false;

        return true;
    }


    /* this isn´t used at all lol
    public function createParticipation($participator_name, $app_id)
    {
        if ($this->db->errno != 0) return false;

        $query = "INSERT INTO participation('participator_name', 'appointment_id') VALUES(?,?);";
        $stmt = $this->db->prepare($query);

        if (!$stmt) return false;

        $stmt->bind_param("si", $participator_name, $app_id);
        $stmt->execute();

        if ($stmt->errno != 0) return false;

        return true;
       
    } */
}
