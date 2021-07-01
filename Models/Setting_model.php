<?php

class Setting_model extends Connection{
    private static $Money = null;

    public function __construct(){
        parent::__construct();
    }
    public function TypeMoney( $value, $model1 ) {
        try {
            $this->db->pdo->beginTransaction();
            $response = $this->db->Select1( '*', 'tsetting', null, null );
           
            if (is_array($response)){
                $response = $response ['results'];
                if ( 0 == count( $response ) ){
                    self::$Money = 'L.';
                    $model1->TypeMoney = self::$Money;
                    $model1->Interests = 0.0;
                    $query1 = 'INSERT INTO tsetting (ID,TypeMoney,Interests) VALUES (:ID,:TypeMoney,:Interests)';
                    $sth = $this->db->pdo->prepare( $query1 );
                    $sth->execute( ( array )$model1 );
                }else{
                    switch ( $value ) {
                        case '1':
                        self::$Money = 'L.';
                        break;
                        case '2':
                        self::$Money = '$';
                        break;
    
                    }
                    echo  var_dump( $response );
                    $model1->ID = $response[0]['ID'];
                    $model1->TypeMoney = self::$Money;
                    $model1->Interests = $response[0]['Interests'];
                    $query2 =  'UPDATE tsetting SET ID = :ID,TypeMoney = :TypeMoney,Interests = :Interests WHERE ID = '.$response[0]['ID'];
                    $sth = $this->db->pdo->prepare( $query2 );
                    $sth->execute( ( array )$model1 );
                }
                $this->db->pdo->commit();
                return true;
            }
        } catch (Throwable $th) {
            $this->db->pdo->rollBack();
            echo $th->getMessage();
            return false;
        }
    }
    public function SetInterests( $value, $model1 ){
        try {
            $this->db->pdo->beginTransaction();
            $response = $this->Setting();
            if ( is_array( $response ) ){
                if ( 0 == count( $response ) ){
                    self::$Money = 'L.';
                    $model1->TypeMoney = self::$Money;
                    $model1->Interests = $value;
                    $query1 = 'INSERT INTO tsetting (ID,TypeMoney,Interests) VALUES (:ID,:TypeMoney,:Interests)';
                    $sth = $this->db->pdo->prepare( $query1 );
                    $sth->execute( ( array )$model1 );
                }else{
                    $model1->ID = $response['ID'];
                    $model1->TypeMoney = $response['TypeMoney'];
                    $model1->Interests = $value;
                    $query2 =  'UPDATE tsetting SET ID = :ID,TypeMoney = :TypeMoney,Interests = :Interests WHERE ID = '.$response['ID'];
                    $sth = $this->db->pdo->prepare( $query2 );
                    $sth->execute( ( array )$model1 );
                }
                $this->db->pdo->commit();
                return true;
            }
        } catch (Throwable $th) {
            $this->db->pdo->rollBack();
            echo $th->getMessage();
            return false;
        }
    }
    public function Setting() {
        $response = $this->db->Select1( '*', 'tsetting', null, null );
        if ( is_array( $response ) ){
            $response = $response ['results'];
        }
        return $response[0];
    }
}
?>