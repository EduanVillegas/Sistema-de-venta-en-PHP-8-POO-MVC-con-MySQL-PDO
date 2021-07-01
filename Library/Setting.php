<?php

class Setting extends Connection{
    public static $setting = null;

    public function __construct() {
        parent::__construct();
        $this->GetSetting();
        $this->GetTClientReport();
    }
    public function Setting(){
        $response = $this->db->Select1( '*', 'tsetting', null, null );
        if ( is_array( $response ) ){
            $response = $response ['results'];
            return $response[0];
        }
    }
    public function GetSetting(){
        $response = $this->Setting();
        if ( is_array( $response ) ){
            if ( 0 < count( $response ) ) {
                self::$setting = array(
                    "TypeMoney" => $response['TypeMoney'],
                    "Interests" => $response['Interests']
                );
            }
        } 
    }
    //CLIENTES

    public function GetTClientReport() {
        $currentDate = date( 'Y-m-d' );
        $response = $this->db->Select1( '*', 'treports_clients', null, null );
        if ( is_array( $response ) ) {
            foreach ( $response['results'] as $key => $value ){
                if ( $value['Deadline'] != null ){
                    $days = ( strtotime( $value['Deadline'] ) - strtotime( $currentDate ) ) / ( 60 * 60 * 24 );
                    if ( 0 >  $days ) {
                        $this->InterestsMora( $value, $days );
                    }
                }
            }
        }
    }
    private function InterestsMora( $cliente, $days ){
        $interests = self::$setting['Interests'];
        if ( 0.0 != $interests ){
            $where1 = ' WHERE IdCustomer = :IdCustomer AND InitialDate = :InitialDate';
            $response1 = $this->db->Select1( '*', 'tcustomer_interests', $where1, array( 'IdCustomer' => $cliente['IdClients'], 'InitialDate' => $cliente['DatePayment'] ) );
            if ( is_array( $response1 ) ) {
                $response1 = $response1['results'];
            }
            $where2 = ' WHERE IdCustomer = :IdCustomer AND InitialDate = :InitialDate AND Canceled = :Canceled';
            $response2 = $this->db->Select1( '*', 'tcustomer_interests', $where2, array( 'IdCustomer' => $cliente['IdClients'], 'InitialDate' => $cliente['DatePayment']
            , 'Canceled' => false ) );
            if ( is_array( $response2 ) ) {
                $response2 = $response2['results'];
            }
            if ( is_array( $response1 ) && is_array( $response2 ) ){
                $days = abs( $days );
                $porcentaje = $interests / 100;
                $moratorioMensual = $cliente['Monthly'] * $porcentaje;
                $moratorioDia = $moratorioMensual / 30;
                $interes = $moratorioDia * $days;
                $count1 = count( $response1 );
                $count2 = count( $response2 );
                if ( $count2 == 0 ){
                    for ( $i = 0; $i < $days; $i++ ) {
                        $this->insert( $i, false,$response2,$cliente, $moratorioDia);
                    }
                }else{
                    if ( $count1 < $days ) {
                        if ( $count2 < $days ){
                            $interesDias = $days - $count1;
                            for ( $i = 1; $i <= $interesDias; $i++ ) {
                                $this->insert( $i, true ,$response2,$cliente,$moratorioDia );
                            }
                        }
                    }
                }
            }
        }
    }
    private function insert( $days, $value ,$response2 ,$cliente,$interes){
        try {
            $this->db->pdo->beginTransaction();
            $fecha = null;
            if ( $value ){
                $data = end( $response2 );
                $fecha = $data['Date'];
            }else{
                $fecha = $cliente['Deadline'];
            }
            $dateNow =  strtotime($fecha."+ ".$days." days");
            $date =  date('Y-m-d',$dateNow);
            $query1 = 'INSERT INTO tcustomer_interests (Deadline,Debt,Monthly,Interests,Date,Canceled,IdCustomer,InitialDate) VALUES (:Deadline,:Debt,:Monthly,:Interests,:Date,:Canceled,:IdCustomer,:InitialDate)';
            $data = array(
                "Deadline" => $cliente['Deadline'],
                "Debt" => $cliente['Debt'],
                "Monthly" => $cliente['Monthly'],
                "Interests" => $interes,
                "Date" =>  $date,
                "Canceled" => false,
                "IdCustomer" => $cliente["IdClients"],
                "InitialDate" => $cliente["DatePayment"],
            );
            $sth = $this->db->pdo->prepare( $query1 );
            $sth->execute( $data);
            $this->db->pdo->commit();
        } catch (Throwable $th) {
            $this->db->pdo->rollBack();
            echo $th->getMessage();
        }
    }
}
?>