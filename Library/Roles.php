<?php
declare (strict_types = 1);
class Roles extends Connection
{
    public function __construct() {
        parent::__construct();
    }
    public function SetRoles(){
        try {
            $this->db->pdo->beginTransaction();
            $listRoles = array("Admin","User");
            $where = " WHERE Role = :Role";
            foreach ($listRoles as $key => $value){
                $roles = $this->db->Select1("Role",'TRoles',$where,array('Role' => $value));
                if (is_array($roles)){
                    if (0 == count($roles['results'])){
                        $query = "INSERT INTO TRoles (Role) VALUES (:Role)";
                        $sth = $this->db->pdo->prepare($query);
                        $sth->execute((array)$this->TRoles(array($value)));
                    }
                }else{
                    break;
                    return $roles;
                }
            }
            $this->db->pdo->commit();
        } catch (Throwable $th) {
            $this->db->pdo->rollBack();
            return $th->getMessage();
        }
    }
    public function TRoles(array $array){
        return new class($array){
            var $Role;
            function __construct($array){
                if(0 < count($array)){
                    $this->Role = $array[0];
                }
            }
        };
    }
    public function GetRoles(){
        $roles = $this->db->select1("*",'TRoles',null,null);
        if (is_array($roles)){
            if (0 < count($roles['results'])){
                return $roles['results'];
            }
        }else{
            return $roles;
        }
    }
}

?>