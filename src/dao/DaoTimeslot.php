<?php
require_once "src/metier/Timeslot.php";
require_once "src/metier/Speciality.php";
class DaoTimeslot {
    private string $host;
    private string $dbname;
    private string $user;
    private string $pass;
    private PDO $db;

    public function __construct(string $host, string $dbname, int $port, string $user, string $pass) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->pass = $pass;

        try {
            $this->db = new PDO("pgsql:dbname=" . $dbname . ";host=" . $host . ";port=" . $port, $user, $pass);
        } catch (PDOException $e) {
            $erreurs = [];
	        echo $e->getMessage();
        }
    }

    public function insertTimeslot(string $date, float $duration, int $idUser, string $description){
        $statement = $this->db->prepare("INSERT INTO timeslot (date, duration, id_user, description) VALUES (:date, :duration, :idUser, :description)");
        $statement->bindParam(":date", $date);
        $statement->bindParam(":duration", $duration);
        $statement->bindParam(":idUser", $idUser);
        $statement->bindParam(":description", $description);
        $statement->execute();
    }

    public function getTimeslotsByIdUser(int $idUser){
        $statement = $this->db->prepare("SELECT t.id, t.date, t.duration, t.id_user, t.id_user_validated, COALESCE(is_validated, 2) as is_validated, t.description, u.id as id_user, u.name, u.surname, u.cycle, u.mail, u.is_admin
        FROM timeslot t
        JOIN users u ON t.id_user = u.id
        WHERE id_user = :idUser
        ORDER BY date DESC LIMIT 10");

        $statement->bindParam(":idUser", $idUser);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $timeslots = [];
        foreach($result as $timeslot){
            $user = new User($timeslot["id_user"], $timeslot["name"], $timeslot["surname"], $timeslot["cycle"], $timeslot["mail"], $timeslot["is_admin"]);
            $timeslots[] = new Timeslot($timeslot["id"], new DateTime($timeslot["date"]), $timeslot["duration"], $timeslot["description"] ,$timeslot["id_user_validated"], $timeslot["is_validated"], $user);
        }

        return $timeslots;
    }

    public function deleteTimeslotById(int $id){
        $statement = $this->db->prepare("DELETE FROM timeslot WHERE id = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();
    }

    public function getFullTimeslotsByIdUser(int $idUser){
        $statement = $this->db->prepare("SELECT t.id, t.date, t.duration, t.id_user, t.id_user_validated, COALESCE(is_validated, 2) as is_validated, t.description, u.id as id_user, u.name, u.surname, u.cycle, u.mail, u.is_admin
        FROM timeslot t
        JOIN users u ON t.id_user = u.id
        WHERE id_user = :idUser
        ORDER BY date DESC");
        $statement->bindParam(":idUser", $idUser);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $timeslots = [];
        foreach($result as $timeslot){
            $user = new User($timeslot["id_user"], $timeslot["name"], $timeslot["surname"], $timeslot["cycle"], $timeslot["mail"], $timeslot["is_admin"]);
            $timeslots[] = new Timeslot($timeslot["id"], new DateTime($timeslot["date"]), $timeslot["duration"], $timeslot["description"] ,$timeslot["id_user_validated"], $timeslot["is_validated"], $user);
        }
    
        return $timeslots;
    }

    public function getUnvalidatedTimeslots(){
        $statement = $this->db->prepare("SELECT t.id, t.date, t.duration, t.id_user, t.id_user_validated, COALESCE(is_validated, 2) as is_validated, t.description, u.id as id_user, u.name, u.surname, u.cycle, u.mail, u.is_admin
        FROM timeslot t JOIN users u ON t.id_user = u.id
        WHERE is_validated IS NULL 
        ORDER BY date DESC");
        
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $timeslots = [];
        foreach($result as $timeslot){
            $user = new User($timeslot["id_user"], $timeslot["name"], $timeslot["surname"], $timeslot["cycle"], $timeslot["mail"], $timeslot["is_admin"]);
            $timeslots[] = new Timeslot($timeslot["id"], new DateTime($timeslot["date"]), $timeslot["duration"], $timeslot["description"] ,$timeslot["id_user_validated"], $timeslot["is_validated"], $user);
        }
    
        return $timeslots;
    }

    public function validateTimeslot(int $idTimeslot, int $idUser){
        $statement = $this->db->prepare("UPDATE timeslot SET id_user_validated = :idUser, is_validated = 1 WHERE id = :idTimeslot");
        $statement->bindParam(":idUser", $idUser);
        $statement->bindParam(":idTimeslot", $idTimeslot);
        $statement->execute();
    }

    public function refuseTimeslot(int $idTimeslot, int $idUser){
        $statement = $this->db->prepare("UPDATE timeslot SET id_user_validated = :idUser, is_validated = 0 WHERE id = :idTimeslot");
        $statement->bindParam(":idUser", $idUser);
        $statement->bindParam(":idTimeslot", $idTimeslot);
        $statement->execute();
    }

    public function getTotalHours(int $idUser){
        $statement = $this->db->prepare("SELECT SUM(t.duration) as somme from timeslot t WHERE id_user = :id AND is_validated = 1 GROUP BY id_user");
        $statement->bindParam(":id", $idUser);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getRecentTimeslots(){
        $statement = $this->db->prepare("SELECT t.id, t.date, t.duration, t.id_user, t.id_user_validated, COALESCE(is_validated, 2) as is_validated, t.description, u.id as id_user, u.name, u.surname, u.cycle, u.mail, u.is_admin, s.type , s.id as id_speciality, uv.name, uv.surname
                                        FROM timeslot t JOIN users u ON t.id_user = u.id
                                        JOIN speciality s ON u.id_speciality = s.id
                                        JOIN users uv ON uv.id = t.id_user_validated
                                        WHERE is_validated = 1
                                        OR is_validated = 0
                                        ORDER BY date DESC
                                        LIMIT 10");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        

    }


};