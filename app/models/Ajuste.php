<?php
    class Adjustment
    {
        public $idBooking;
        public $reason;
        public $cost;

        public function createAdjustment(){
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepararConsulta("INSERT INTO ajustes(idBooking, reason, cost) VALUES (:idBooking, :reason, :cost)");
            $query->bindValue(':idBooking', $this->idBooking, PDO::PARAM_INT);
            $query->bindValue(':cost', $this->cost, PDO::PARAM_INT);
            $query->bindValue(':reason', $this->reason, PDO::PARAM_STR);
            
            $query->execute();

            return $objAccesoDatos->obtenerUltimoId();
        }
    }
