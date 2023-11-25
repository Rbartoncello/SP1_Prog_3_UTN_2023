<?php
require_once './models/Habitacion.php';
require_once './utils/isRoomDataValid.php';
require_once './interfaces/IApiUsable.php';

class RoomController extends Room implements IApiUsable
{

  public function GetOne($request, $response, $args)
    {

        $queryParams = $request->getQueryParams();
        $id = $queryParams['nroCliente'] ?? null;
        $type = $queryParams['tipoCliente'] ?? null;

        if(isset($id) && isset($type)) 
        {
          $client = Client::GetByTypeAndNumber($id, $type);
          $payload = response($client);
        } else 
        {
          $payload = response(array("error" => "numero de idetificacion ya registrado"), 400, false);
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function Create($request, $response, $args)
    {
        $params = $request->getParsedBody();

        if(isset($params["nroCliente"]) && isset($params["fechaEntrada"]) && isset($params["fechaSalida"]) && isset($params["tipoHabitacion"]) && isset($params["importe"]))
        {
          $id_guest = $params["nroCliente"];
          $checkInDate = $params["fechaEntrada"];
          $checkOutDate = $params["fechaSalida"];
          $type = $params["tipoHabitacion"];
          $cost = $params["importe"];
      
          if(isRoomDataValid($type, $cost))
          {
            try {
              $newBooking = new Room();
              $newBooking->checkInDate = $checkInDate;
              $newBooking->checkOutDate = $checkOutDate;
              $newBooking->type = $type;
              $newBooking->cost = $cost;
              $newBooking->id_guest = $id_guest;
              $newBooking->type = $type;
              $id = $newBooking->createRoom();
              $payload = response(array("response" => "la reserva $id fue creada con exito"));
            } catch (Exception $e) 
            {
              $payload = response(array("error" => $e->getMessage()), 400, false);
            }
          } else 
          {
              $payload = response(array("error" => "mal ingreso de parametros"), 400, false);
          }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
      }
    }

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        Usuario::modificarUsuario($nombre);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
