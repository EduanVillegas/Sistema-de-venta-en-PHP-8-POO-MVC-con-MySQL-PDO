<?php
class UserController extends Controllers
{
    public function __construct() {
        parent::__construct();
        //session_start();
    }
    public function Register(){
        if (null != Session::getSession("User")){
            $roles = $this->role->GetRoles();
            $model1 = Session::getSession("model1");
            $model2 = Session::getSession("model2");
            if(null != $model1 || null != $model2){
                $array1 = unserialize( $model1);
                $array2 = unserialize( $model2);
                if(is_array($array1) && is_array($array2)){
                    $model1 = $this->TUser($array1);
                    $model2 = $this->TUser($array2);
                    $rol = array(array("Role" => $model1->Role));
                    $i = 1;
                    foreach ($roles as $key => $value) {
                        if($model1->Role != $value["Role"]){
                            $rol[$i] = array("Role" => $value["Role"]);
                            $i++;
                        }
                    }
                    $this->view->Render($this,"register",$model1,$model2,$rol);
                }else{
                    $this->view->Render($this,"register",null,null,$roles); 
                }
            }else{
                $this->view->Render($this,"register",null,null,$roles); 
            }
        
        }else{
            header("Location:".URL);
        }
        
    }
    public function AddUser(){
        $user = Session::getSession("User");
        if(null != $user){
            if ("Admin"== $user["Role"]){
                $execute = true;
                $image = null;
                if (empty($_POST["nid"])){
                   $nid = "Ingrese el nid";
                   $execute = false;
                }
                if (empty($_POST["name"])){
                    $name = "Ingrese el nombre";
                    $execute = false;
                }
                if (empty($_POST["lastname"])){
                    $lastname = "Ingrese el apellido";
                    $execute = false;
                }
                if (empty($_POST["email"])){
                    $email = "Ingrese el email";
                    $execute = false;
                }else{
                    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                        $email = "Ingrese un email valido";
                        $execute = false;
                    }
                }
                if (0 ==$_POST["iduser"]){
                    if (empty($_POST["password"])){
                        $password = "Ingrese el password";
                        $execute = false;
                    }
                }
               
                if (empty($_POST["phone"])){
                    $phone = "Ingrese el telefono";
                    $execute = false;
                }
                if (empty($_POST["direction"])){
                    $direction = "Ingrese la direccion";
                    $execute = false;
                }
                if (empty($_POST["user"])){
                    $user = "Ingrese el usuario";
                    $execute = false;
                }
                if ("Seleccione un role" == $_POST["role"]){
                    $role = "Seleccione un role";
                    $execute = false;
                }
               
                if (isset($_FILES['file'])){
                    $archivo =  $_FILES['file']["tmp_name"];
                    if($archivo != null){
                        $contents = file_get_contents($archivo); 
                       $image = base64_encode($contents);
                    }else{
                        $model1 = Session::getSession("model1");
                        if(null != $model1){
                            $array1 = unserialize($model1);
                            $image = $array1["Image"];
                        }else{
                            $img = file_get_contents(URL.RQ."images/default.png"); 
                            $image = base64_encode($img);
                        }
                    }
                }
                $model1 = array(
                    "IdUser"=>$_POST["iduser"] ?? null,
                    "NID"=>$_POST["nid"] ?? null,
                    "Name"=>$_POST["name"] ?? null,
                    "LastName"=>$_POST["lastname"] ?? null,                            
                    "Email"=>$_POST["email"] ?? null,
                    "Password"=>$_POST["password"] ?? null,
                    "Phone"=>$_POST["phone"] ?? null,
                    "Direction"=>$_POST["direction"] ?? null,
                    "User"=>$_POST["user"] ?? null,
                    "Role"=>$_POST["role"] ?? null,
                    "Image"=>$image ?? null
                 );
                Session::setSession('model1',serialize($model1));
                if ($execute){
                    $value = $this->model->AddUser($this->TUser($model1));
                    if (is_numeric($value)){
                        Session::setSession('model1',"");
                        Session::setSession('model2',"");
                        if ($value == 0) {
                            header('Location: User');
                        } else {
                            header('Location: '.URL.'User/Details/'.$value);
                        }
                        
                        
                    }else{
                        Session::setSession('model2',serialize(array(
                            "Role"=>$value,
                        )));
                        header('Location: Register');
                    }
                   
                }else{
                    Session::setSession('model2',serialize(array(
                        "NID"=>$nid ?? null,
                        "Name"=>$name ?? null,
                        "LastName"=>$lastname ?? null,
                        "Email"=>$email ?? null,
                        "Password"=>$password ?? null,
                        "Phone"=>$phone ?? null,
                        "Direction"=>$direction ?? null,
                        "User"=>$user ?? null,
                        "Role"=>$role ?? null,
                    )));
                    header('Location: Register');
                }
                
            }else{
                Session::setSession('model1',serialize(array()));
                Session::setSession('model2',serialize(array(
                    "Role"=>"No cuenta con los permisos requeridos para ejecutar esta acción",
                )));
                header('Location: Register');
            }
        }
    }
    public function User($page){
        if (null != Session::getSession("User")){
            $item = null;
            $filter = (isset($_GET["filtrar"])) ? $_GET["filtrar"] : "" ;
            $response = $this->model->GetUsers($this->paginador,$filter,
            $page,1,"User/User",URL);
            if (is_array($response)){
                if (0 < count($response["results"])){
                    $response =$response;
                   // echo var_dump($response);
                }else{
                    $response = array(
                        "results" => null,
                        "pagi_info" => null,
                        "pagi_navegacion" => "No hay datos que mostrar"
                    );
                }
            }else{
                $response = array(
                    "results" => null,
                    "pagi_info" => null,
                    "pagi_navegacion" => $response
                );
            }
            $this->view->Render($this,"user", $response,null,null); 
        }else{
            header("Location:".URL); 
        }
        
    }
    public function Details($id){
        if (null != Session::getSession("User")){
            $response = $this->model->GetUsers(null,$id,
            null,null,null,null);
            if (is_array($response)){
                if (0 < count($response["results"])){
                    $this->view->Render($this,"details",$response["results"],null,null); 
                }else{
                    header('Location: User/User');
                }
            }else{
                header('Location: User/User');
            }
        }else{
            header("Location:".URL); 
        }
        
    }
    public function Cancel(){
        Session::setSession('model1',"");
        Session::setSession('model2',"");
        header('Location: Register');
    }
}

?>