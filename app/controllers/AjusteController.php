<?php
require_once './models/ajuste.php';
require_once './interfaces/IApiUsable.php';

class AdjustmentController extends Adjustment
{
    public function Create($request, $response, $args)
    {

        $queryParams = $request->getQueryParams();
        $idBooking = $queryParams['idReserva'] ?? null;
        
        $params = $request->getParsedBody();
        $reason = $params['motivo'];
        $cost = $params['ajuste'];

        if(isset($idBooking) && isset($reason) && isset($cost)){
          try {
            if(Room::UpdateCost($idBooking, $cost) > 0){
              $adjustment = new Adjustment();
              $adjustment->idBooking = $idBooking;
              $adjustment->reason = $reason;
              $adjustment->cost = $cost;

              $payload = response(array("response" => $adjustment->createAdjustment()));
            }
          } catch (Exception $e) {
              $payload = response(array("error" => $e->getMessage()), 400, false);
          }
        } else {
          $payload = response(array("error" => "parametros nulos"), 400, false);
        }
        

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}