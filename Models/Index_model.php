<?php
class Index_model extends Connection
{
    public function __construct() {
        parent::__construct();
    }
    public function Login($model){
        $where = " WHERE Email = :Email";
        $param = array('Email' => $model->Email);
        $response = $this->db->Select1("*",'tusers',$where,$param);
        if (is_array($response)){
            $response = $response['results'];
            if (0 < count($response)){
                if (password_verify($model->Password,$response[0]["Password"])) {
                    $data = array(
                        "IdUser" => $response[0]["IdUser"],
                        "Name" => $response[0]["Name"],
                        "LastName" => $response[0]["LastName"],
                        "Role" => $response[0]["Role"],
                        "User" => $response[0]["User"],
                        "Email" => $response[0]["Email"],
                    );
                    Session::setSession("User",$data);
                    return 1;
                }else{
                    return "Contraseña incorrecta";
                }
            }else{
                return "El email no esta registrado";
            }
        }else{
            return  $response;
        }
    }
}


?>