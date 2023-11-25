<?php
function isUserDataValid($typeIdentification, $numberIdentification, $phone, $email, $type){
    if(in_array($typeIdentification, ["DNI", "LE", "LC", "PASAPORTE"])){
        if(is_numeric($numberIdentification)){
            if(is_numeric($phone)){
                if(strpos($email, "@") !== false && strpos($email, ".com") !== false){
                    if(in_array($type, ["INDI", "CORPO"]) ){
                        return true;
                    } else {
                        echo json_encode(['error' => 'Tipo cliente mal ingresado']);
                    }
                } else {
                    echo json_encode(['error' => 'Formato invalido de correo']);
                }
            } else {
                echo json_encode(['error' => 'Telefono no es numerico']);
            }
        } else {
            echo json_encode(['error' => 'Docuemento no es numerico']);
        }
    } else {
        echo json_encode(['error' => 'Tipo de documento no es valido']);
    }
    return false;
}