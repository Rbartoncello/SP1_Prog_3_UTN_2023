<?php
function isRoomDataValid($type, $cost){
    return (in_array($type, ['simple', 'doble', 'suite']) && is_numeric($cost));
}
