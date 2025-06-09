<?php 
require_once "src/metier/User.php";
class Timeslot {
    private int $id;
    private DateTime $date;
    private float $duration;
    private string $description;
    private ?int $idUserValidated;
    private ?int $isValidated;
    private User $user;


    public function __construct(int $id, DateTime $date, float $duration, string $description, ?int $idUserValidated, ?int $isValidated, User $user){
        $this->id = $id;
        $this->date = clone $date;
        $this->duration = $duration;
        $this->description = $description;
        if($idUserValidated == NULL){
            $this->idUserValidated = NULL;
        }
        else $this->idUserValidated = $idUserValidated;
        if($isValidated == NULL){
            $this->isValidated = NULL;
        }
        else $this->isValidated = $isValidated;
        $this->user = $user;
    }
    public function getId() : int{
        return $this->id;
    }
    public function getDate() : DateTime{
        return $this->date;
    }
    public function getDuration() : float{
        return $this->duration;
    }
    public function getIdUserValidated() : ?int{
        return $this->idUserValidated;
    }
    public function getIsValidated() : ?int{
        return $this->isValidated;
    }
    public function getDescription() : string{
        return $this->description;
    }
    public function getUser() : User{
        return $this->user;
    }



};