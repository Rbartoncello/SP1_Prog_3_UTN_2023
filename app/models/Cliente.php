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

        public static function Save($client){
            if(!Client::Exist($client)){
                $data = Client::Get();
                $data[] = $client;
                echo (file_put_contents(RECORD_CLIENTS, json_encode($data, JSON_PRETTY_PRINT)))? json_encode(['response' => 'ingresado']): ['error' => 'no se puedo cargar nueva cliente'];
            } else {
                echo (file_put_contents(RECORD_CLIENTS, json_encode(Client::uploadData($client), JSON_PRETTY_PRINT)))? json_encode(['response' => 'actulizado']): ['error' => 'no se puedo actualizar cliente'];
            }
            
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

        public static function uploadData($client, $data = null) {
            if(!$data) {
                $data = Client::Get();
            }

            foreach ($data as &$item) {
                if (strval($item->numberIdentification) === strval($client->numberIdentification)){
                    $id_old = $item->id;
                    $item = $client;
                    $item->id = $id_old;
                }
            }
            return $data;
        }

        public static function Upload($client, $data = null) {
            if(!$data) {
                $data = Client::Get();
            }

            foreach ($data as &$item) {
                if ((strval($item->id) === strval($client->id)) && ($item->type === $client->type)){
                    $item = $client;
                }
            }
            return $data;
        }
    }
