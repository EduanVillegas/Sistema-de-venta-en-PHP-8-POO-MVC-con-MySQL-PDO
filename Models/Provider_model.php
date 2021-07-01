<?php

class Provider_model extends Connection
{
    private $Money = null;

    public function __construct()
    {
        parent::__construct();
        $this->Money = Setting::$setting['TypeMoney'];
    }

    public function AddProvider($model1, $model2)
    {
        try {
            $this->db->pdo->beginTransaction();
            $where = ' WHERE Email = :Email';
            $response = $this->db->Select1('*', 'tproviders', $where, array('Email' => $model1->Email));
            if (is_array($response)) {
                $response = $response['results'];
                if (0 == $model1->IdProvider) {
                    if (0 == count($response)) {
                        $model1->State = true;
                        $model1->Date = date('Y-m-d');
                        $query1 = 'INSERT INTO tproviders (IdProvider,Provider,Email,Phone,Direction,Image,Date,State) VALUES (:IdProvider,:Provider,:Email,:Phone,:Direction,:Image,:Date,:State)';
                        $sth = $this->db->pdo->prepare($query1);
                        $sth->execute((array)$model1);
                        $id = $this->db->pdo->lastInsertId();

                        $model2->Debt = 0;
                        $model2->Monthly = 0;
                        $model2->Changes = 0;
                        $model2->LastPayment = 0;
                        $model2->DatePayment = null;
                        $model2->CurrentDebt = 0;
                        $model2->DateDebt = null;
                        $model2->Ticket = '0000000000';
                        $model2->IdProviders = $id;

                        $query2 = 'INSERT INTO treports_providers (IdReport,Debt,Monthly,Changes,LastPayment,DatePayment,CurrentDebt,DateDebt,Ticket,IdProvider) VALUES (:IdReport,:Debt,:Monthly,:Changes,:LastPayment,:DatePayment,:CurrentDebt,:DateDebt,:Ticket,:IdProviders,Agreement)';
                        $sth = $this->db->pdo->prepare($query2);
                        $sth->execute((array)$model2);
                    } else {
                        return 'El email ya esta registrado';
                    }
                } else {
                    $where = ' WHERE IdProvider = :IdProvider';
                    $response1 = $this->db->Select1('*', 'tproviders', $where, array('IdProvider' => $model1->IdProvider));
                    $model1->State = $response1[0]['State'];
                    $model1->Date = $response1[0]['Date'];
                    $query2 =  'UPDATE tproviders SET IdProvider = :IdProvider,Provider = :Provider,Email = :Email,Phone = :Phone,Direction = :Direction,Image = :Image,State = :State,Date = :Date WHERE IdProvider = ' . $model1->IdProvider;
                    if (0 == count($response)) {
                        $sth = $this->db->pdo->prepare($query2);
                        $sth->execute((array)$model1);
                    } else {
                        if ($model1->IdProvider == $response[0]['IdProvider']) {
                            $sth = $this->db->pdo->prepare($query2);
                            $sth->execute((array)$model1);
                        } else {
                            return 'El email ya esta registrado';
                        }
                    }
                }
                $this->db->pdo->commit();
                return $model1->IdProvider;
            } else {
                return $response;
            }
        } catch (Throwable $th) {
            $this->db->pdo->rollBack();
            echo $th->getMessage();
        }
    }

    public function GetProviders($paginador, $filter, $page, $register, $method, $url)
    {
        if ($paginador != null) {
            $where = ' WHERE Provider LIKE :Provider OR Email LIKE :Email';
            $array = array(
                'Provider' => '%' . $filter . '%',
                'Email' => '%' . $filter . '%'
            );
            return $paginador->paginador('*', 'tproviders', $method, $register, $page, $where, $array, $url);
        } else {
            $where = ' WHERE IdProvider = :IdProvider';
            return  $this->db->Select1('*', 'tproviders', $where, array('IdProvider' => $filter));
        }
    }

    public function GetTProviderReport($idProvider)
    {
        $where = ' WHERE IdProvider = :IdProvider';
        $condition = 'tproviders.IdProvider = treports_providers.IdProviders';
        return  $this->db->Select3('*', 'tproviders', 'treports_providers', $condition, $where, array('IdProvider' => $idProvider));
    }

    public function Payment($SetSection, $fees, $payment, $idProvider, $model1, $user, $model2, $RadioOptions1)
    {
        $message = null;
        $dataProvider =  $this->GetTProviderReport($idProvider);
        $change = 0.0;
        $currentDate = date('Y-m-d');
        if (is_array($dataProvider)) {
            $dataProvider = $dataProvider['results'];
        } else {
            return $dataProvider;
        }
        switch ($SetSection) {
            case '1':
                try {
                    $this->db->pdo->beginTransaction();
                    if ($fees > 0) {
                        $currentDebt = (float)$dataProvider[0]['CurrentDebt'];
                        if ($currentDebt > 0) {
                            $monthly = (float)$dataProvider[0]['Monthly'];
                            $valor1 = ceil($currentDebt / $monthly);
                            $coutas = (int)ceil($valor1);
                            if ($coutas >= $fees) {
                                $monthly = $monthly * $fees;
                                $payment = (float)$payment;
                                if ($payment > $monthly) {
                                    $change = $payment - $monthly;
                                } else {
                                    $change = 0.0;
                                }
                                $ticket = Codes::codesTickets($dataProvider[0]['Ticket']);
                                $_currentDebt = $currentDebt - $monthly;
                                if ($_currentDebt == 0.0 || $_currentDebt == 0) {
                                    $model1->Debt = 0.0;
                                    $model1->Monthly = 0.0;
                                    $model1->Changes = 0.0;
                                    $model1->LastPayment = 0.0;
                                    $model1->DatePayment = null;
                                    $model1->CurrentDebt = 0.0;
                                    $model1->DateDebt = null;
                                    $model1->Ticket = '0000000000';
                                    $model1->IdProviders = $idProvider;
                                } else {
                                    $model1->IdReport = $dataProvider[0]['IdReport'];
                                    $model1->Debt = $dataProvider[0]['Debt'];
                                    $model1->Monthly = $dataProvider[0]['Monthly'];
                                    $model1->Changes = $change;
                                    $model1->LastPayment = $payment;
                                    $model1->DatePayment = $currentDate;
                                    $model1->CurrentDebt = $_currentDebt;
                                    $model1->DateDebt = $dataProvider[0]['DateDebt'];
                                    $model1->Ticket = $ticket;
                                    $model1->IdProviders = $idProvider;
                                    $model1->Agreement = $dataProvider[0]['Agreement'];

                                    $query1 =  'UPDATE treports_providers SET IdReport = :IdReport,Debt = :Debt,Monthly = :Monthly,Changes = :Changes,LastPayment = :LastPayment,DatePayment = :DatePayment,CurrentDebt = :CurrentDebt,DateDebt = :DateDebt,Ticket = :Ticket,IdProviders = :IdProviders ,Agreement = :Agreement WHERE IdReport = ' . $dataProvider[0]['IdReport'];

                                    $sth = $this->db->pdo->prepare($query1);
                                    $sth->execute((array)$model1);

                                    $model2->Debt = $dataProvider[0]['Debt'];
                                    $model2->Payment = $payment;
                                    $model2->Changes = $change;
                                    $model2->CurrentDebt = $_currentDebt;
                                    $model2->Monthly = $dataProvider[0]['Monthly'];
                                    $model2->PreviousDebt = $dataProvider[0]['CurrentDebt'];
                                    $model2->Date = $currentDate;
                                    $model2->DateDebt = $dataProvider[0]['DateDebt'];
                                    $model2->Ticket = $ticket;
                                    $model2->IdUser = $user['IdUser'];
                                    $model2->IdProviders = $idProvider;
                                    $model2->Agreement = $dataProvider[0]['Agreement'];

                                    $query2 = 'INSERT INTO tpayments_providers (IdPayments,Debt,Monthly,Changes,Payment,Date,PreviousDebt,CurrentDebt,DateDebt,Ticket,IdUser,Agreement,IdProviders) VALUES (:IdPayments,:Debt,:Monthly,:Changes,:Payment,:Date, :PreviousDebt,:CurrentDebt,:DateDebt,:Ticket,:IdUser,:Agreement,:IdProviders)';

                                    $sth = $this->db->pdo->prepare($query2);
                                    $sth->execute((array)$model2);
                                    //echo var_dump($model1);
                                    $name = $dataProvider[0]['Provider'];
                                    $tickets = new Ticket();
                                    $tickets->TextoCentro('Sistema de ventas PDHN');
                                    // imprime en el centro
                                    $tickets->TextoIzquierda('Direccion');
                                    $tickets->TextoIzquierda('La Ceiba, Atlantidad');
                                    $tickets->TextoIzquierda('Tel 658912146');
                                    $tickets->LineasGuion();
                                    $tickets->TextoCentro('FACTURA');
                                    $tickets->LineasGuion();
                                    $tickets->TextoIzquierda('Factura:' . $ticket);
                                    $tickets->TextoIzquierda('Proveedor:' . $name);
                                    $tickets->TextoIzquierda('Fecha:' . $currentDate);
                                    $tickets->TextoIzquierda('Usuario:' . $user['Name'] . ' ' . $user['LastName']);
                                    $tickets->LineasGuion();
                                    $tickets->TextoCentro('Cretito ' . $this->Money . DecimalFormat::number_format($dataProvider[0]['Debt']));
                                    $tickets->LineasGuion();
                                    $Agreement = $dataProvider[0]["Agreement"] == 'Q' ? "Cuotas quincenales:" : "Cuotas por meses:";
                                    $tickets->TextoExtremo($Agreement, $this->Money . DecimalFormat::number_format($dataProvider[0]['Monthly']));
                                    $tickets->TextoExtremo('Numero de cuotas pagadas:', $fees);
                                    $tickets->TextoExtremo('Total a pagar:', $this->Money . DecimalFormat::number_format($monthly));
                                    $tickets->LineasGuion();
                                    $tickets->TextoExtremo('Deuda anterior:', $this->Money . DecimalFormat::number_format($dataProvider[0]['CurrentDebt']));
                                    $tickets->TextoExtremo('Pago:', $this->Money . DecimalFormat::number_format($payment));
                                    $tickets->TextoExtremo('Cambio:', $this->Money . DecimalFormat::number_format($change));
                                    $tickets->TextoExtremo('Deuda actual:', $this->Money . DecimalFormat::number_format($_currentDebt));
                                    $tickets->TextoCentro('PDHN');
                                    $tickets->PDF("Provider/Debt", "$ticket-$currentDate", "$name-$idProvider");

                                    $this->db->pdo->commit();
                                    return (int)$SetSection;
                                }
                            } else {
                                $message =  "Se sobrepaso de las cuotas a pagar";
                            }
                        } else {
                            $message =  "El sistema no contiene deuda con el proveedor";
                        }
                    } else {
                        $message =  "Seleccione la cantidad de cuotas a pagar";
                    }
                } catch (Throwable $th) {
                    $this->db->pdo->rollBack();
                    echo $message = $th->getMessage();
                }
                break;
            case '2':
                try {
                    $this->db->pdo->beginTransaction();
                    $payment = (float)$payment;
                    $query1 =  'UPDATE treports_providers SET Monthly = :Monthly,Agreement = :Agreement WHERE IdReport = ' . $dataProvider[0]['IdReport'];
                    $agreement = $RadioOptions1 == 1 ? 'Q' : 'M';
                    $sth = $this->db->pdo->prepare($query1);
                    $sth->execute(array(
                        'Monthly' => $payment,
                        'Agreement' => $agreement
                    ));
                    $this->db->pdo->commit();
                    return (int)$SetSection;
                } catch (Throwable $th) {
                    $this->db->pdo->rollBack();
                    $message = $th->getMessage();
                }
                break;
        }
        //echo var_dump( $dataProvider );
        return $message;
    }
    public function GetPayments($date1, $date2, $paginador)
    {
        $idProvider = Session::getSession('idProvider');
        $where = ' WHERE IdProviders = :IdProviders';
        if (null != $date1 || null != $date2) {
            $date1 = strtotime(str_replace('/', '-', $date1));
            $date2 = strtotime(str_replace('/', '-', $date2));
            $fecha_actual = strtotime(date('d-m-Y', time()));
            if ($date1 == $date2 && $date1 == $fecha_actual && $fecha_actual == $date2) {
                return $this->db->Select5('*', 'tpayments_providers', $where, array('IdProviders' => $idProvider), "IdPayments", 5);
            } else {
                if ($date1 < $date2) {
                    $response = $this->db->Select1('*', 'tpayments_providers', $where, array('IdProviders' => $idProvider));
                    $array = array();
                    $a = 0;
                    if (is_array($response)) {
                        foreach ($response['results'] as $key => $value) {
                            $date3 = strtotime($value['Date']);
                            if ($date1 == $date3 ||  $date1 < $date3) {
                                $array[$a] = $value;
                                $a++;
                            }
                        }
                        $array1 = array();
                        $a = 0;
                        foreach ($array as $key => $value) {
                            $date3 = strtotime($value['Date']);
                            if ($date2 == $date3 ||  $date3 <= $date2) {
                                $array1[$a] = $value;
                                $a++;
                            }
                        }
                        return array("results" => array_reverse($array1));
                    }
                } else {
                    return 'La fecha final debe ser mayor a la fecha de inicio';
                }
            }
        } else {
            return $this->db->Select5('*', 'tpayments_providers', $where, array('IdProviders' => $idProvider), "IdPayments", 5);
        }
    }
    public function DetailsDebt($idDebt)
    {
        $where = ' WHERE tpayments_providers.IdPayments = :IdPayments';
        $condition1 = 'tpayments_providers.IdProviders=tproviders.IdProvider';
        $condition2 = 'tpayments_providers.IdUser=tusers.IdUser';
        $columns = 'IdProvider,Provider,IdPayments,Debt,Payment,Changes,CurrentDebt,tpayments_providers.Date,DateDebt,Monthly,PreviousDebt,Ticket,Agreement,Name,LastName';
        $payments = $this->db->Select4($columns, 'tpayments_providers', 'tproviders', 'tusers', $condition1, $condition2, $where, array('IdPayments' => $idDebt));
        if (is_array($payments)) {
            return  $payments['results'][0];
        } else {
            return  $payments;
        }
    }
}
