<?php
class IndexController extends Controllers
{
    public function __construct() {
       parent::__construct();
    }
    public function Index()
    {
       // $this->role->SetRoles();
        $model1 = Session::getSession("model1");
        $model2 = Session::getSession("model2");
        if(null != $model1 || null != $model2){
            $array1 = unserialize( $model1);
            $array2 = unserialize( $model2);
            if(is_array($array1) && is_array($array2)){
                $model1 = $this->TUser($array1);
                $model2 = $this->TUser($array2);
                
                $this->view->Render($this,"index",$model1,$model2,null);
            }else{
                $this->view->Render($this,"index",null,null,null); 
            }
        }else{
            $this->view->Render($this,"index",null,null,null);
        }
        
    }
    public function Login(){
        $execute = true;
        if (empty($_POST["email"])){
            $email = "Ingrese el email";
            $execute = false;
        }else{
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                $email = "Ingrese un email valido";
                $execute = false;
            }
        }
        if (empty($_POST["password"])){
            $password = "Ingrese el password";
            $execute = false;
        }
        $model1 = array(                         
            "Email"=>$_POST["email"] ?? null,
            "Password"=>$_POST["password"] ?? null,
         );
        Session::setSession('model1',serialize($model1));
        if ($execute){
            $value = $this->model->Login($this->TUser($model1));
            if (is_numeric($value)){
                Session::setSession('model1',"");
                Session::setSession('model2',"");
                header('Location: '.URL.'Main/Main');
            }else{
                Session::setSession('model2',serialize(array(
                    "Role"=>$value,
                )));
                header('Location: '.URL);
            }
        }else{
            Session::setSession('model2',serialize(array(
                "Email"=>$email ?? null,
                "Password"=>$password ?? null,
            )));
            header('Location: '.URL);
        }
    }
    public function Logout(){
        Session::setSession('model1',"");
        Session::setSession('model2',"");
        Session::setSession('User',"");
        header('Location: '.URL);
    }
}


?>