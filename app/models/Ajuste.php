<?php
define("RECORD_AJUSTMENTS", "ajuste.json");

    class Ajustment
    {
        public $id;
        public $reason;

        public function __construct($id, $reason){
            $this->id = $id;
            $this->reason = $reason; 
        }

        public static function Save($ajustment){
            $data = Ajustment::Get();
            $data[] = $ajustment;
            return file_put_contents(RECORD_AJUSTMENTS, json_encode($data, JSON_PRETTY_PRINT));
        }

        public static function Get() {
            if(!file_exists(RECORD_AJUSTMENTS)){
                return [];
            }

            $jsonContent = file_get_contents(RECORD_AJUSTMENTS);
            if ($jsonContent !== false) {
                $data = json_decode($jsonContent);
                return $data !== null ? $data : [];                
            }
            return [];
        }
    }
