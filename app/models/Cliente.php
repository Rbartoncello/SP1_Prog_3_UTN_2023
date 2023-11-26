<?php

define("RECORD_CLIENTS", "hoteles.json");


    class Client
    {
        public $id;
        public $name;
        public $surname;
        public $typeIdentification;
        public $numberIdentification;
        public $email;
        public $type;
        public $country;
        public $city;
        public $phone;
        public $paymentMethod = 'efectivo';

        public function __construct(){
            $this->id = date("mdhis");
        }

        public function createClient()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepararConsulta("INSERT INTO clientes(id, name, surname, typeIdentification, numberIdentification, email, type, country, city, phone, paymentMethod) VALUES (:id, :name, :surname, :typeIdentification, :numberIdentification, :email, :type, :country, :city, :phone, :paymentMethod)");
            $query->bindValue(':id', $this->id, PDO::PARAM_INT);
            $query->bindValue(':name', $this->name, PDO::PARAM_STR);
            $query->bindValue(':surname', $this->surname, PDO::PARAM_STR);
            $query->bindValue(':typeIdentification', $this->typeIdentification, PDO::PARAM_STR);
            $query->bindValue(':numberIdentification', $this->numberIdentification, PDO::PARAM_INT);
            $query->bindValue(':email', $this->email, PDO::PARAM_STR);
            $query->bindValue(':type', $this->type, PDO::PARAM_STR);
            $query->bindValue(':country', $this->country, PDO::PARAM_STR);
            $query->bindValue(':city', $this->city, PDO::PARAM_STR);
            $query->bindValue(':phone', $this->phone, PDO::PARAM_INT);
            $query->bindValue(':paymentMethod', $this->paymentMethod, PDO::PARAM_STR);

            $query->execute();

            return $objAccesoDatos->obtenerUltimoId();
        }

        public static function UpdateClient($id, $name, $surname, $typeIdentification, $numberIdentification, $email, $type, $country, $city, $phone, $paymentMethod)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepararConsulta(
                "UPDATE clientes 
                SET 
                    name=:name,
                    surname=:surname,
                    typeIdentification=:typeIdentification,
                    numberIdentification=:numberIdentification,
                    email=:email,
                    type=:type,
                    country=:country,
                    city=:city,
                    phone=:phone,
                    paymentMethod=:paymentMethod
                WHERE 
                    id=:id"
            );
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->bindValue(':name', $name, PDO::PARAM_STR);
            $query->bindValue(':surname', $surname, PDO::PARAM_STR);
            $query->bindValue(':typeIdentification', $typeIdentification, PDO::PARAM_STR);
            $query->bindValue(':numberIdentification', $numberIdentification, PDO::PARAM_INT);
            $query->bindValue(':email', $email, PDO::PARAM_STR);
            $query->bindValue(':type', $type, PDO::PARAM_STR);
            $query->bindValue(':country', $country, PDO::PARAM_STR);
            $query->bindValue(':city', $city, PDO::PARAM_STR);
            $query->bindValue(':phone', $phone, PDO::PARAM_INT);
            $query->bindValue(':paymentMethod', $paymentMethod, PDO::PARAM_STR);

            $query->execute();

            return $objAccesoDatos->obtenerUltimoId();
        }

        public static function Get() {
            if(!file_exists(RECORD_CLIENTS)){
                return [];
            }

            $jsonContent = file_get_contents(RECORD_CLIENTS);
            if ($jsonContent !== false) {
                $data = json_decode($jsonContent);
                return $data !== null ? $data : [];                
            }
            return [];
        }
    
        public static function Exist($numberIdentification){
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepararConsulta("SELECT * FROM clientes WHERE numberIdentification = :numberIdentification");
            $query->bindValue(':numberIdentification', $numberIdentification, PDO::PARAM_STR);
            $query->execute();

            return $query->fetchObject('Client');
        }

        public static function GetByTypeAndNumber($id, $type)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepararConsulta("SELECT * FROM clientes WHERE id = :id and type = :type");
            $query->bindValue(':id', $id, PDO::PARAM_STR);
            $query->bindValue(':type', $type, PDO::PARAM_STR);
            $query->execute();
            $client = $query->fetchObject('Client');

            if ($client === false) {
                return array();
            }
            return $client;
        }

        public static function DeleteClient($id, $type)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepararConsulta("UPDATE clientes SET deleted=:deleted WHERE id=:id AND type=:type");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->bindValue(':type', $type, PDO::PARAM_STR);
            $query->bindValue(':deleted', true, PDO::PARAM_BOOL);
            $query->execute();

            return $query->rowCount();
        }
    }
