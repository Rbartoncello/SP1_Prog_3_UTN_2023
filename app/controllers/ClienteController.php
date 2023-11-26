<?php
require_once './models/Cliente.php';
require_once './interfaces/IApiUsable.php';
require_once './utils/response.php';
require_once './utils/isUserDataValid.php';

class ClientController extends Client implements IApiUsable
{
    public function GetOne($request, $response, $args)
    {

        $queryParams = $request->getQueryParams();
        $id = $queryParams['nroCliente'] ?? null;
        $type = $queryParams['tipoCliente'] ?? null;

        if(isset($id) && isset($type)) 
        {
          try {
            $client = Client::GetByTypeAndNumber($id, $type);
            $payload = response($client);
          } catch (Exception $e) {
            $payload = response(array("error" => $e->getMessage()), 400, false);
          }
          
        } else 
        {
          $payload = response(array("error" => "mal ingreso de parametros"), 400, false);
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function Create($request, $response, $args)
    {
        $params = $request->getParsedBody();

        if(isset($params["nombre"]) && isset($params["apellido"]) && isset($params["tipoDocumento"]) && isset($params["email"]) && isset($params["tipoCliente"]) && isset($params["pais"]) && isset($params["ciudad"]) &&isset($params["telefono"])
          )
        {
          $name = $params["nombre"];
          $surname = $params["apellido"];
          $typeIdentification = $params["tipoDocumento"];
          $numberIdentification = $params["documento"];
          $email = $params["email"];
          $type = $params["tipoCliente"];
          $country = $params["pais"];
          $city = $params["ciudad"];
          $phone = $params["telefono"];
      
          if(isUserDataValid($typeIdentification, $numberIdentification, $phone, $email, $type))
          {
            try 
            {
                $newClient = new Client();
                $newClient->name = $name;
                $newClient->surname = $surname;
                $newClient->typeIdentification = $typeIdentification;
                $newClient->numberIdentification = $numberIdentification;
                $newClient->email = $email;
                $newClient->type = $type;
                $newClient->country = $country;
                $newClient->city = $city;
                $newClient->phone = $phone;
                $id = $newClient->createClient();
                $payload = response(array("response" => "el usuario $id fue registrado con exito"));
            } catch (Exception $e) 
            {
              $payload = response(array("error" => $e->getMessage()), 400, false);
            }
          } else 
          {
              $payload = response(array("error" => "en el ingreso de parametros"), 400, false);
          }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
      }
    }

    public function Update($request, $response, $args)
    {
        $queryParams = $request->getQueryParams();
        $id = $queryParams['nroCliente'] ?? null;
        $params = $request->getParsedBody();

        if(isset($params["nombre"]) && isset($params["apellido"]) && isset($params["tipoDocumento"]) && isset($params["email"]) && isset($params["tipoCliente"]) && isset($params["pais"]) && isset($params["ciudad"]) &&isset($params["telefono"])
          )
        {
          $name = $params["nombre"];
          $surname = $params["apellido"];
          $typeIdentification = $params["tipoDocumento"];
          $numberIdentification = $params["documento"];
          $email = $params["email"];
          $type = $params["tipoCliente"];
          $country = $params["pais"];
          $city = $params["ciudad"];
          $phone = $params["telefono"];
          $paymentMethod = $params["medioPago"];
      
          if(isUserDataValid($typeIdentification, $numberIdentification, $phone, $email, $type))
          {
            try 
            {
                Client::UpdateClient(
                  $id, $name, $surname, $typeIdentification, $numberIdentification, $email, $type, $country, $city, $phone, $paymentMethod
                );
                $payload = response(array("response" => "el usuario $id fue modificado con exito"));
            } catch (Exception $e) 
            {
                $payload = response(array("error" => $e->getMessage()), 400, false);
            }
          } else 
          {
              $payload = response(array("error" => "en el ingreso de parametros"), 400, false);
          }
        } else 
        {
            $payload = response(array("error" => "parametros nulos"), 400, false);
        }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    public function Delete($request, $response, $args)
    {
        $queryParams = $request->getQueryParams();
        $id = $queryParams['nroCliente'] ?? null;
        $type = $queryParams['tipoCliente'] ?? null;

        if(isset($id) && isset($type))
        {
            try 
            {
                if(Client::DeleteClient($id, $type) > 0)
                  $payload = response(array("response" => "el usuario $id fue eliminado con exito"));
                else
                  $payload = response(array("response" => "el usuario $id no exite"));
            } catch (Exception $e) 
            {
                $payload = response(array("error" => $e->getMessage()), 400, false);
            }
        } else 
        {
            $payload = response(array("error" => "parametros nulos"), 400, false);
        }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }
}
