<?php
class Room
    {
        public $id;
        public $checkInDate;
        public $checkOutDate; 
        public $type; 
        public $cost; 
        public $id_guest;
        public $deleted = false;

        public function __construct(){
            $this->id = date("mdhis");
        }

        public function createRoom()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepararConsulta("INSERT INTO habitacion(id, checkInDate, checkOutDate, type, cost, id_guest) VALUES (:id, :checkInDate, :checkOutDate, :type, :cost, :id_guest)");
            $query->bindValue(':id', $this->id);
            $query->bindValue(':checkInDate', date_format(date_create($this->checkOutDate), 'Y-m-d'));
            $query->bindValue(':checkOutDate', date_format(date_create($this->checkInDate), 'Y-m-d'));
            $query->bindValue(':type', $this->type, PDO::PARAM_STR);
            $query->bindValue(':cost', $this->cost, PDO::PARAM_INT);
            $query->bindValue(':id_guest', $this->id_guest, PDO::PARAM_INT);
            
            $query->execute();

            return $objAccesoDatos->obtenerUltimoId();
        }

        public static function UpdateCost($id, $cost)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepararConsulta("UPDATE habitacion SET cost=:cost  WHERE id=:id");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->bindValue(':cost', $cost, PDO::PARAM_INT);
            $query->execute();

            return $query->rowCount();
        }

        public static function DeleteRoom($id, $idGuest)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepararConsulta(
                "UPDATE habitacion SET deleted=:deleted WHERE id=:id AND id_guest=:id_guest"
            );
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->bindValue(':id_guest', $idGuest, PDO::PARAM_INT);
            $query->bindValue(':deleted', true, PDO::PARAM_STR);

            $query->execute();

            return $query->rowCount();
        }

        public static function GetById($id)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepararConsulta("SELECT * FROM habitacion WHERE id=:id");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $query->fetchObject('Room');
        }


    }