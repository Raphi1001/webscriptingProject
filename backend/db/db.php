<?php
/*
include_once "./models/appointment.php";
include_once "./models/comments.php";
include_once "./models/dates.php";
include_once "./models/participation.php";
include_once "./models/user.php";
include_once "./models/votes.php";
*/

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
     * Returns a list of all Users as an array of User objects
     * 
     * @return array Array of User objects
     */
    public function getAppointmentList()
    {
        $sql = "SELECT * FROM appointments";

        $result = $this->db->query($sql);

        $data = array();
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($data, new Appointment($row["app_id"], $row["title"], $row["location"], $row["description"], $row["vote_expire"], $row["creator_name"]));
        }
        return $data;
    }


    public function createAppointment($title, $location, $description, $vote_expire, $creator_name)
    {
        $query = "INSERT INTO appointments('title', 'location', 'description', 'vote_expire', 'creator_name') VALUES(?,?,?,?,?);";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssss", $title, $location, $description, $vote_expire, $creator_name);
        $stmt->execute();
        //kein result weil es werden nur daten eingefügt und ja so lala ist das jetzt
        //pls help
    }



    public function createComment($creator_name, $app_id, $comment)
    {
        $query = "INSERT INTO comments('creator_name', 'appointment_id', 'comment') VALUES(?,?,?);";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sis", $creator_name, $app_id, $comment);
        $stmt->execute();
        //no result lol 
    }


    public function createVote($vote_name, $date_id)
    {
        $query = "INSERT INTO votes('vote_name', 'date_id') VALUES(?,?);";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $vote_name, $date_id);
        $stmt->execute();
        //no result lol 
    }


    public function createDate($date, $app_id)
    {
        $query = "INSERT INTO dates('date', 'appointment_id') VALUES(?,?);";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $name, $app_id);
        $stmt->execute();
        //no result lol 
    }

    public function createParticipation($participator_name, $app_id)
    {
        $query = "INSERT INTO participation('participator_name', 'appointment_id') VALUES(?,?);";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $participator_name, $app_id);
        $stmt->execute();
        //no result lol 
    }






    /**
     * Returns the Username pow
     * 
     * @param int $userid UserId
     * @return string Username
     */
    public function getUsername($userid)
    {
        $uname = "";
        if ($this->db->connect_errno == 0 && $stmt = $this->db->prepare("CALL getUsername(?)")) {
            $stmt->bind_param("i", $userid);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();

                if ($result && $result->num_rows) {
                    $uname = $result->fetch_object()->Username;
                }
                $result->free_result();
            }
        }
        return $uname;
    }



    /**
     * Checks if email already exists
     * 
     * @param string $emailaddr e-mail address
     * @return bool TRUE if exists, FALSE if not
     */
    public function getEmail($emailaddr)
    {
        if ($this->db->errno == 0) {
            $emailaddr = $this->db->escape_string($emailaddr);
            if ($stmt = $this->db->prepare("CALL getEmail(?)")) {
                $stmt->bind_param("s", $emailaddr);
                if ($stmt->execute() && $result = $stmt->get_result()) {
                    if ($result->num_rows) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Returns admin status of user
     * 
     * @param string $username Username
     * @return bool Admin status
     */
    public function getAdminStatus($username)
    {
        if ($this->db->errno == 0) {
            $username = $this->db->escape_string($username);
            if ($stmt = $this->db->prepare("CALL getAdmin(?)")) {
                $stmt->bind_param("s", $username);
                if ($stmt->execute() && $result = $stmt->get_result()) {
                    if ($row = $result->fetch_array()) {
                        if ($row[0]) return true;
                    }
                }
            }
        }
        return false;
    }





    /**
     * Updates password of a user
     * 
     * @param string $username Username
     * @param string $newPassword Hash of the new Password
     * @return bool TRUE is successful, FALSE is not
     */
    public function updatePassword($username, $newPassword)
    {
        if ($this->db->errno == 0) {
            $username = $this->db->escape_string($username);
            $newPassword = $this->db->escape_string($newPassword);
            $stmt = $this->db->prepare("CALL updatePassword(?, ?)");

            if ($stmt) {
                $stmt->bind_param("ss", $newPassword, $username);
                $stmt->execute();

                if ($stmt->errno == 0) {
                    return true;
                }
            }
        }
        return false;
    }



    /**
     * Deletes User from database
     * 
     * @param int $id ID of the User
     * @return bool TRUE is successful, FALSE is not
     */
    public function deleteUser($id)
    {
        $id = $this->db->escape_string($id);

        if (is_numeric($id) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL deleteUser(?)")) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }


    /**
     * Changes isActive status of User
     * 
     * @param bool $isActive Is active status
     * @param int $userid ID of the User
     * @return bool TRUE is successful, FALSE is not
     */
    public function changeActiveStatus($isActive, $userid)
    {
        $userid = $this->db->escape_string($userid);

        if (is_numeric($userid) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL changeActiveStatus(?, ?)")) {
            $stmt->bind_param("ii", $isActive, $userid);
            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Escapes special chars in every property of user to prevent SQL injections
     */
    private function escapeAllVars($array)
    {
        foreach ($array as $value) {
            if (is_string($value)) $value = $this->db->escape_string($value);
            else if (is_array($value)) {
                foreach ($value as $arrElement) {
                    $arrElement = $this->db->escape_string($arrElement);
                }
            }
        }
        return $array;
    }



    /**
     * Inserts Comment into the database
     * 
     * @param Comment $newComment Comment object
     * @return bool TRUE if successful, FALSE if not
     */
    public function insertComment($newComment)
    {
        if ($this->db->errno == 0) {
            $stmt = $this->db->prepare("CALL insertComment(?, ?, ?)");
            $commentArray = $newComment->getAllVars();
            $commentArray = $this->escapeAllVars($commentArray);

            $userId = $commentArray['userId'];
            $postId = $commentArray['postId'];
            $message = $commentArray['message'];

            if ($stmt) {
                $stmt->bind_param("iis", $userId, $postId, $message);
                $stmt->execute();
                if ($stmt->errno == 0) {
                    return true;
                }
            }
        }
        return false;
    }





    /**
     * Updates Comment in database
     * 
     * @param Comment $newComment Comment object
     * @return bool TRUE if successful, FALSE if not
     */
    public function updateComment($newComment)
    {
        if ($this->db->errno == 0) {
            $stmt = $this->db->prepare("CALL updateComment(?, ?)");
            $commentArray = $newComment->getAllVars();
            $commentArray = $this->escapeAllVars($commentArray);

            $commentId = $commentArray['commentId'];
            $message = $commentArray['message'];

            if ($stmt) {
                $stmt->bind_param("is", $commentId, $message);
                $stmt->execute();
                if ($stmt->errno == 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Deletes a Comment from database
     * 
     * @param int $commentId Comment ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function deleteComment($commentId)
    {
        if ($this->db->errno == 0 && $stmt = $this->db->prepare("CALL deleteComment(?)")) {
            $commentId = $this->db->escape_string($commentId);

            $stmt->bind_param("i", $commentId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }






    /**
     * Returns a list of all Tags from a Post
     * 
     * @return array Array of Tags
     */
    public function getPostTags($postId)
    {
        $tags = array();
        if ($this->db->connect_errno == 0 && $stmt = $this->db->prepare("CALL getPostTags(?)")) {
            $postId = $this->db->escape_string($postId);
            $stmt->bind_param("i", $postId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows) {
                while ($row = $result->fetch_object())
                    array_push($tags, $row->Tagname);
            }
            $result->free_result();
        }
        return $tags;
    }





    /**
     * Inserts Post into the database
     * 
     * @param Post $post Post object
     * @return bool TRUE if successful, FALSE if not
     */
    public function insertPost($post)
    {
        $success = false;


        if ($this->db->errno == 0) {
            $stmt = $this->db->prepare("CALL insertPost(?, ?, ?, ?)");

            $postArray = $post->getAllVars();
            $postArray = $this->escapeAllVars($postArray);
            $userid = $postArray['userId'];
            $imgName = $postArray['imgObject'] ? $postArray['imgObject']->getProperty("fileName") : NULL;
            $description = $postArray['description'];
            $privacy = $postArray['privacy'];

            if ($stmt) {
                $stmt->bind_param(
                    "issi",
                    $userid,
                    $imgName,
                    $description,
                    $privacy
                );
                $stmt->execute();
                if ($stmt->errno == 0) {
                    $result = $stmt->get_result();
                    $stmt->close();
                    if ($result && $result->num_rows) {
                        //insert tags
                        $postId = $result->fetch_object()->PostId;
                        $success = $this->insertPostTags($postId, $postArray["tags"]);
                    }
                    $result->free_result();
                }
            }
        }
        return $success;
    }

    /**
     * Inserts Tags for a Post into the database
     * 
     * @param int $postId Post_ID
     * @param array $tags Array of tags
     * @return bool TRUE if successful, FALSE if not
     */
    public function insertPostTags($postId, $tags)
    {
        $postId = $this->db->escape_string($postId);
        if (is_numeric($postId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL insertPostTag(?, ?)")) {
            foreach ($tags as $tag) {
                $tag = $this->db->escape_string($tag);
                $stmt->bind_param("is", $postId, $tag);
                $stmt->execute();
                if ($stmt->errno != 0) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Gets like from database
     * 
     * @param int $postId Beitrags_ID
     * @param int $userId User_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function getLike($postId, $userId)
    {
        $postId = $this->db->escape_string($postId);
        $userId = $this->db->escape_string($userId);
        if (is_numeric($postId) && is_numeric($userId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL getLike(?, ?)")) {
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();
                if ($result && $result->num_rows != 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Gets dislike from database
     * 
     * @param int $postId Beitrags_ID
     * @param int $userId User_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function getDislike($postId, $userId)
    {
        $postId = $this->db->escape_string($postId);
        $userId = $this->db->escape_string($userId);
        if (is_numeric($postId) && is_numeric($userId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL getDislike(?, ?)")) {
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                $result = $stmt->get_result();
                if ($result && $result->num_rows != 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Removes dislike and adds like
     * 
     * @param int $postId Beitrags_ID
     * @param int $userId User_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function likePost($postId, $userId)
    {
        $postId = $this->db->escape_string($postId);
        $userId = $this->db->escape_string($userId);
        if (is_numeric($postId) && is_numeric($userId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL likePost(?, ?)")) {
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Removes like and adds dislike
     * 
     * @param int $postId Beitrags_ID
     * @param int $userId User_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function dislikePost($postId, $userId)
    {
        $postId = $this->db->escape_string($postId);
        $userId = $this->db->escape_string($userId);
        if (is_numeric($postId) && is_numeric($userId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL dislikePost(?, ?)")) {
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Removes like
     * 
     * @param int $postId Beitrags_ID
     * @param int $userId User_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function removeLike($postId, $userId)
    {
        $postId = $this->db->escape_string($postId);
        $userId = $this->db->escape_string($userId);
        if (is_numeric($postId) && is_numeric($userId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL removeLike(?, ?)")) {
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Removes dislike
     * 
     * @param int $postId Beitrags_ID
     * @param int $userId User_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function removeDislike($postId, $userId)
    {
        $postId = $this->db->escape_string($postId);
        $userId = $this->db->escape_string($userId);
        if (is_numeric($postId) && is_numeric($userId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL removeDislike(?, ?)")) {
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Removes all likes from a post
     * 
     * @param int $postId Beitrags_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function removeAllLikes($postId)
    {
        $postId = $this->db->escape_string($postId);
        if (is_numeric($postId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL removeAllLikes(?)")) {
            $stmt->bind_param('i', $postId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Removes all dislikes from a post
     * 
     * @param int $postId Beitrags_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function removeAllDislikes($postId)
    {
        $postId = $this->db->escape_string($postId);
        if (is_numeric($postId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL removeAllDislikes(?)")) {
            $stmt->bind_param('i', $postId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Deletes Tags of a Post in database
     * 
     * @param int $postId Post_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function deleteAllPostTags($postId)
    {
        $postId = $this->db->escape_string($postId);
        if (is_numeric($postId) && $this->db->errno == 0 && $stmt = $this->db->prepare("CALL deleteAllPostTags(?)")) {
            $stmt->bind_param("i", $postId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Updates Post in database
     * 
     * @param Post $post Post object
     * @return bool TRUE if successful, FALSE if not
     */
    public function updatePost($post)
    {
        if ($this->db->errno == 0) {
            $stmt = $this->db->prepare("CALL updatePost(?, ?, ?, ?)");

            $postArray = $post->getAllVars();
            $postArray = $this->escapeAllVars($postArray);
            $postId = $postArray['postId'];
            $imgName = $postArray['imgObject'] ? $postArray['imgObject']->getProperty("fileName") : NULL;
            $description = $postArray['description'];
            $privacy = $postArray['privacy'];

            if ($stmt) {
                $stmt->bind_param(
                    "issi",
                    $postId,
                    $imgName,
                    $description,
                    $privacy
                );
                $stmt->execute();
                if ($stmt->errno == 0) {
                    $stmt->close();
                    return $this->deleteAllPostTags($postId) && $this->insertPostTags($postId, $postArray['tags']);
                }
            }
        }
        return false;
    }

    /**
     * Deletes Post from database
     * 
     * @param int $postId Post_ID
     * @return bool TRUE if successful, FALSE if not
     */
    public function deletePost($postId)
    {
        if ($this->db->errno == 0 && $stmt = $this->db->prepare("CALL deletePost(?)")) {
            $postId = $this->db->escape_string($postId);

            $stmt->bind_param("i", $postId);
            $stmt->execute();
            if ($stmt->errno == 0) {
                return true;
            }
        }
        return false;
    }
}
