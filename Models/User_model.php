<?php
class User_model extends Connection
{
    public function __construct(){
        parent::__construct();
    }
    public function AddUser($model){
        try {
            $this->db->pdo->beginTransaction();
            $where = " WHERE Email = :Email";
            $response = $this->db->Select1("*",'tusers',$where,array('Email' => $model->Email));
            if (is_array($response)){
                $response = $response['results'];
                if (0 == $model->IdUser){
                    if (0 == count($response)){
                        $model->Is_active = true;
                        $model->State = true;
                        $model->Date = date("Y-m-d");
                        $model->Password = password_hash($model->Password,PASSWORD_DEFAULT);
                        $query = "INSERT INTO tusers (IdUser,NID,Name,LastName,Email,Password,Phone,Direction,User,Role,Image,Is_active,State,Date) VALUES (:IdUser,:NID, :Name,:LastName,:Email,:Password,:Phone,:Direction,:User,:Role,:Image,:Is_active,:State,:Date)";
                       
                    }else{
                        return "El email ya esta registrado";
                    }
                }else{
                    $model->Is_active = $response[0]["Is_active"];
                    $model->State = $response[0]["State"];
                    $model->Date = $response[0]["Date"];
                    $model->Password = $response[0]["Password"];
                    $query =  "UPDATE tusers SET IdUser = :IdUser,NID = :NID,Name = :Name,LastName = :LastName,Email = :Email,Password = :Password,Phone = :Phone,Direction = :Direction,User = :User,Role = :Role,Image = :Image,Is_active = :Is_active,State = :State,Date = :Date WHERE IdUser = ".$model->IdUser;
                }
                $sth = $this->db->pdo->prepare($query);
                $sth->execute((array)$model);
                $this->db->pdo->commit();
                return $model->IdUser;
            }else{
                return $response;
            }
           
        } catch (Throwable $th) {
            $this->db->pdo->rollBack();
            return $th->getMessage();
        }
       
    }
    public function GetUsers($paginador,$filter,$page,$register,$method,$url){
        if($paginador != null){
            $where = " WHERE Name LIKE :Name OR LastName LIKE :LastName OR Email LIKE :Email";
            $array = array(
                'Name' => '%'.$filter.'%',
                'LastName' => '%'.$filter.'%',
                'Email' => '%'.$filter.'%'
            );
            return $paginador->paginador("*","tusers",$method,$register,$page,$where,$array,$url);
        }else{
            $where = " WHERE IdUser = :IdUser";
            return  $this->db->Select1("*",'tusers',$where,array('IdUser' => $filter));
        }
       
    }
}


?>