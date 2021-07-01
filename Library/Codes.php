<?php
class Codes 
{
    public static function codesTickets($Codigo){
        $ticket = null;
        if ($Codigo == "0000000000"){
            $ticket = "0000000001";
        }else {
            if ($Codigo == "9999999999"){
                $ticket = "0000000001"; 
            }else {
                $cod = (int)  $Codigo;
                $cod++;
                $ticket = str_pad($cod, 10, "0", STR_PAD_LEFT);
            }
        }
        return $ticket;
    }
}

?>