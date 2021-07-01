<?php

class Client_model extends Connection
{
    private $Money = null;

    public function __construct()
    {
        parent::__construct();
        $this->Money = Setting::$setting['TypeMoney'];
    }

    public function AddClient($model1, $model2)
    {
        try {
            $this->db->pdo->beginTransaction();
            $model1->Credit = $model1->Credit == null ? 0 : 1;
            $where = ' WHERE Email = :Email';
            $response = $this->db->Select1('*', 'tclients', $where, array('Email' => $model1->Email));
            if (is_array($response)) {
                $response = $response['results'];
                if (0 == $model1->IdClient) {
                    if (0 == count($response)) {
                        $model1->State = true;
                        $model1->Date = date('Y-m-d');
                        $query1 = 'INSERT INTO tclients (IdClient,NID,Name,LastName,Email,Phone,Direction,Image,Credit,Date,State) VALUES (:IdClient,:NID, :Name,:LastName,:Email,:Phone,:Direction,:Image,:Credit,:Date,:State)';

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
                        $model2->Deadline = null;
                        $model2->IdClients = $id;

                        $query2 = 'INSERT INTO treports_clients (IdReport,Debt,Monthly,Changes,LastPayment,DatePayment,CurrentDebt,DateDebt,Ticket,Deadline,IdClient) VALUES (:IdReport,:Debt,:Monthly,:Changes,:LastPayment,:DatePayment,:CurrentDebt,:DateDebt,:Ticket,:Deadline,:IdClients)';

                        $sth = $this->db->pdo->prepare($query2);
                        $sth->execute((array)$model2);
                    } else {
                        return 'El email ya esta registrado';
                    }
                } else {
                    $model1->State = $response[0]['State'];
                    $model1->Date = $response[0]['Date'];
                    $query2 =  'UPDATE tclients SET IdClient = :IdClient,NID = :NID,Name = :Name,LastName = :LastName,Email = :Email,Phone = :Phone,Direction = :Direction,Image = :Image,Credit = :Credit,State = :State,Date = :Date WHERE IdClient = ' . $model1->IdClient;
                    if (0 == count($response)) {
                        $sth = $this->db->pdo->prepare($query2);
                        $sth->execute((array)$model1);
                    } else {
                        if ($model1->IdClient == $response[0]['IdClient']) {
                            $sth = $this->db->pdo->prepare($query2);
                            $sth->execute((array)$model1);
                        } else {
                            return 'El email ya esta registrado';
                        }
                    }
                }
                $this->db->pdo->commit();
                return $model1->IdClient;
            } else {
                return $response;
            }
        } catch (Throwable $th) {
            $this->db->pdo->rollBack();
            return $th->getMessage();
        }
    }

    public function GetClients($paginador, $filter, $page, $register, $method, $url)
    {
        if ($paginador != null) {
            $where = ' WHERE Name LIKE :Name OR LastName LIKE :LastName OR Email LIKE :Email';
            $array = array(
                'Name' => '%' . $filter . '%',
                'LastName' => '%' . $filter . '%',
                'Email' => '%' . $filter . '%'
            );
            return $paginador->paginador('*', 'tclients', $method, $register, $page, $where, $array, $url);
        } else {
            $where = ' WHERE IdClient = :IdClient';
            return  $this->db->Select1('*', 'tclients', $where, array('IdClient' => $filter));
        }
    }

    public function GetTClientReport($idClient)
    {
        $where = ' WHERE IdClient = :IdClient';
        $condition = 'tclients.IdClient=treports_clients.IdClients';
        return  $this->db->Select3('*', 'tclients', 'treports_clients', $condition, $where, array('IdClient' => $idClient));
    }

    public function Payment($radioOptions, $payment, $idClient, $model1, $model2, $user, $fees, $model3, $model4)
    {
        $change = 0;
        $dataClient =  $this->GetTClientReport($idClient);
        $dataClient = $dataClient['results'];
        switch ($radioOptions) {
            case '1':
                try {
                    $this->db->pdo->beginTransaction();

                    if ($dataClient[0]['Debt'] != '0.0') {
                       // $message =  'El cliente no contiene deuda';
                   // } else {
                        $payment = (float)$payment;
                        $monthly = (float)$dataClient[0]['Monthly'];
                        if ($payment >= $monthly) {
                            $currentDebt = (float)$dataClient[0]['CurrentDebt'];
                            $ticket = Codes::codesTickets($dataClient[0]['Ticket']);
                            if ($payment == $currentDebt || $payment > $currentDebt) {
                                $change = $payment - $currentDebt;
                                $currentDebt = 0.0;
                               // $message = 'Cambio para el cliente ' . $this->Money . number_format($change);
                            } else {
                                $change = $payment - $monthly;
                                $currentDebt = $currentDebt - $monthly;
                               // $message =  'Cambio para el cliente ' . $this->Money . number_format($change);
                            }
                            /* $_payment = number_format( $payment );
                        $_debt = number_format( $dataClient[0]['Debt'] );
                        $_currentDebt = number_format( $currentDebt );
                        $_currentDebtClient = number_format( $dataClient[0]['CurrentDebt'] );

                        $_monthly = number_format( $dataClient[0]['Monthly'] );
                        */
                            $currentDate = date('Y-m-d');
                            //sumo 1 mes
                            $newDate = date('Y-m-d', strtotime($currentDate . '+ 1 month'));

                            $_deadline = $currentDebt == 0.0 ? $currentDate : $newDate;

                            if ($currentDebt == 0.0 || $currentDebt == 0) {
                                $model1->Debt = 0.0;
                                $model1->Monthly = 0.0;
                                $model1->Changes = 0.0;
                                $model1->LastPayment = 0.0;
                                $model1->DatePayment = null;
                                $model1->CurrentDebt = 0.0;
                                $model1->DateDebt = null;
                                $model1->Ticket = '0000000000';
                                $model1->Deadline = null;
                                $model1->IdClients = $idClient;
                            } else {
                                $model1->Debt = $dataClient[0]['Debt'];
                                $model1->Monthly = $dataClient[0]['Monthly'];
                                $model1->Changes = $change;
                                $model1->LastPayment = $payment;
                                $model1->DatePayment = $currentDate;
                                $model1->CurrentDebt = $currentDebt;
                                $model1->DateDebt = $dataClient[0]['DateDebt'];
                                $model1->Ticket = $ticket;
                                $model1->Deadline = $_deadline;
                                $model1->IdClients = $idClient;
                            }
                            $query1 =  'UPDATE treports_clients SET IdReport = :IdReport,Debt = :Debt,Monthly = :Monthly,Changes = :Changes,LastPayment = :LastPayment,DatePayment = :DatePayment,CurrentDebt = :CurrentDebt,DateDebt = :DateDebt,Ticket = :Ticket,Deadline = :Deadline,IdClients = :IdClients WHERE IdReport = ' . $dataClient[0]['IdReport'];

                            $sth = $this->db->pdo->prepare($query1);
                            $sth->execute((array)$model1);

                            $model2->Debt = $dataClient[0]['Debt'];
                            $model2->Payment = $payment;
                            $model2->Changes = $change;
                            $model2->CurrentDebt = $currentDebt;
                            $model2->Monthly = $dataClient[0]['Monthly'];
                            $model2->PreviousDebt = $dataClient[0]['CurrentDebt'];
                            $model2->Date = $currentDate;
                            $model2->Deadline = $_deadline;
                            $model2->DateDebt = $dataClient[0]['DateDebt'];
                            $model2->Ticket = $ticket;
                            $model2->IdUser = $user['IdUser'];
                            $model2->User = $user['Name'] . ' ' . $user['LastName'];
                            $model2->IdClients = $idClient;

                            $query2 = 'INSERT INTO tpayments_clients (IdPayments,Debt,Monthly,Changes,Payment,Date,PreviousDebt,CurrentDebt,DateDebt,Ticket,Deadline,IdUser,User,IdClients) VALUES (:IdPayments,:Debt,:Monthly,:Changes,:Payment,:Date, :PreviousDebt,:CurrentDebt,:DateDebt,:Ticket,:Deadline,:IdUser,:User,:IdClients)';

                            $sth = $this->db->pdo->prepare($query2);
                            $sth->execute((array)$model2);
                            $name = $dataClient[0]['Name'] . ' ' . $dataClient[0]['LastName'];
                            $tickets = new Ticket();
                            $tickets->TextoCentro('Sistema de ventas PDHN');
                            // imprime en el centro
                            $tickets->TextoIzquierda('Direccion');
                            $tickets->TextoIzquierda('La Ceiba, Atlantidad');
                            $tickets->TextoIzquierda('Tel 658912146');
                            $tickets->LineasGuion();
                            $tickets->TextoCentro('FACTURA');
                            // imprime en el centro
                            $tickets->LineasGuion();
                            $tickets->TextoIzquierda('Factura:' . $ticket);
                            $tickets->TextoIzquierda('Cliente:' . $name);
                            $tickets->TextoIzquierda('Fecha:' . $currentDate);
                            $tickets->TextoIzquierda('Usuario:' . $user['Name'] . ' ' . $user['LastName']);
                            $tickets->LineasGuion();
                            $tickets->TextoCentro('Su cretito ' . $this->Money . $dataClient[0]['Debt']);
                            $tickets->TextoExtremo('Cuotas por meses:', $this->Money . $dataClient[0]['Monthly']);
                            $tickets->TextoExtremo('Deuda anterior:', $this->Money . $dataClient[0]['CurrentDebt']);
                            $tickets->TextoExtremo('Pago:', $this->Money . $payment);
                            $tickets->TextoExtremo('Cambio:', $this->Money . $change);
                            $tickets->TextoExtremo('Deuda actual:', $this->Money . $currentDebt);
                            $tickets->TextoExtremo('Próximo pago:', $_deadline);
                            $tickets->TextoCentro('PDHN');
                            $nid = $dataClient[0]['NID'];
                            $tickets->PDF("Client/Debt", "$ticket-$currentDate", "$name-$nid");

                            $this->db->pdo->commit();
                            return $idClient;
                        }/* else {
                            $message =   'El pago debe ser ' . $this->Money . number_format($monthly);
                        }*/
                    }
                } catch (Throwable $th) {
                    $this->db->pdo->rollBack();
                    return $th->getMessage();
                }

                break;

            case '2':
                try {
                    $this->db->pdo->beginTransaction();
                    $currentDate = date('Y-m-d');
                    $amountFees = $this->AmountFees($fees, $idClient);
                    $payment = (float)$payment;
                    $amountFees = (float)$amountFees;
                    $where = ' WHERE IdClient = :IdClient';
                    $response = $this->db->Select1('*', 'tcustomer_interests_reports', $where, array('IdClient' => $idClient));
                    $response = $response['results'];
                    if (0 < count($response)) {
                        $ticket = Codes::codesTickets($response[0]['TicketInterest']);
                    } else {
                        $ticket = '0000000001';
                    }
                    if ($payment >= $amountFees) {
                        if ($payment > $amountFees) {
                            $change = $payment - $amountFees;
                        }
                        $model3->Interests = $amountFees;
                        $model3->Payment = $payment;
                        $model3->Changes = $change;
                        $model3->Fee =  $fees;
                        $model3->Date = $currentDate;
                        $model3->Ticket = $ticket;
                        $model3->IdUser = $user['IdUser'];
                        $model3->User = $user['Name'] . ' ' . $user['LastName'];
                        $model3->IdCustomer = $idClient;

                        $response1 = $this->Tcustomer_interests($idClient);
                        if (is_array($response1)) {
                            $query3 = 'INSERT INTO tpayments_Customer_Interest (IdPaymentsInterest,Interests,Payment,Changes,Fee,Date,Ticket,IdUser,User,IdCustomer) VALUES (:IdPaymentsInterest,:Interests,:Payment,:Changes,:Fee,:Date, :Ticket,:IdUser,:User,:IdCustomer)';
                            $sth = $this->db->pdo->prepare($query3);
                            $sth->execute((array)$model3);
                            if (0 < count($response1)) {
                                for ($i = 0; $i < $fees; $i++) {
                                    $query4 =  'UPDATE tcustomer_interests SET Canceled = :Canceled WHERE IdInterests = ' . $response1[$i]['IdInterests'];
                                    $sth = $this->db->pdo->prepare($query4);
                                    $sth->execute(array('Canceled' => true));
                                }
                            }
                            $response2 = $this->Tcustomer_interests($idClient);
                            if (0 < count($response2)) {
                                $model4->Interests = $amountFees;
                                $model4->Payment = $payment;
                                $model4->Changes = $change;
                                $model4->Fee =  $fees;
                                $model4->TicketInterest = $ticket;
                                $model4->InterestDate = $currentDate;
                                $model4->IdClient = $idClient;
                                if (0 < count($response)) {
                                    $model4->IdinterestReports = $response[0]['IdinterestReports'];

                                    $query5 =  'UPDATE tcustomer_interests_reports SET IdinterestReports = :IdinterestReports,Interests = :Interests,Payment = :Payment,Changes = :Changes,Fee = :Fee,TicketInterest = :TicketInterest,InterestDate = :InterestDate,IdClient = :IdClient WHERE IdinterestReports = ' . $response[0]['IdinterestReports'];

                                    $sth = $this->db->pdo->prepare($query5);
                                    $sth->execute((array)$model4);
                                } else {
                                    $query6 = 'INSERT INTO tcustomer_interests_reports (IdinterestReports,Interests,Payment,Changes,Fee,TicketInterest,InterestDate,IdClient) VALUES (:IdinterestReports,:Interests,:Payment,:Changes,:Fee,:TicketInterest, :InterestDate,:IdClient)';
                                    $sth = $this->db->pdo->prepare($query6);
                                    $sth->execute((array)$model4);
                                }
                            } else {
                                $model4->Interests = 0;
                                $model4->Payment = 0;
                                $model4->Changes = 0;
                                $model4->Fee =  0;
                                $model4->TicketInterest = '0000000000';
                                $model4->InterestDate = $currentDate;
                                $model4->IdClient = $idClient;
                                if (0 < count($response)) {
                                    $model4->IdinterestReports = $response[0]['IdinterestReports'];

                                    $query5 =  'UPDATE tcustomer_interests_reports SET IdinterestReports = :IdinterestReports,Interests = :Interests,Payment = :Payment,Changes = :Changes,Fee = :Fee,TicketInterest = :TicketInterest,InterestDate = :InterestDate,IdClient = :IdClient WHERE IdinterestReports = ' . $response[0]['IdinterestReports'];

                                    $sth = $this->db->pdo->prepare($query5);
                                    $sth->execute((array)$model4);
                                } else {
                                    $query6 = 'INSERT INTO tcustomer_interests_reports (IdinterestReports,Interests,Payment,Changes,Fee,TicketInterest,InterestDate,IdClient) VALUES (:IdinterestReports,:Interests,:Payment,:Changes,:Fee,:TicketInterest, :InterestDate,:IdClient)';
                                    $sth = $this->db->pdo->prepare($query6);
                                    $sth->execute((array)$model4);
                                }
                            }
                        }
                        $name = $dataClient[0]['Name'] . ' ' . $dataClient[0]['LastName'];
                        $tickets = new Ticket();
                        $tickets->TextoCentro('Sistema de ventas PDHN');
                        // imprime en el centro
                        $tickets->TextoIzquierda('Direccion');
                        $tickets->TextoIzquierda('La Ceiba, Atlantidad');
                        $tickets->TextoIzquierda('Tel 658912146');
                        $tickets->LineasGuion();
                        $tickets->TextoCentro('FACTURA');
                        // imprime en el centro
                        $tickets->LineasGuion();
                        $tickets->TextoIzquierda('Factura:' . $ticket);
                        $tickets->TextoIzquierda('Cliente:' . $name);
                        $tickets->TextoIzquierda('Fecha:' . $currentDate);
                        $tickets->TextoIzquierda('Usuario:' . $user['Name'] . ' ' . $user['LastName']);
                        $tickets->LineasGuion();
                        $tickets->TextoCentro('Intereses ' . $this->Money . $amountFees);
                        $tickets->TextoExtremo('Pago:', $this->Money . $payment);
                        $tickets->TextoExtremo('Cambio:', $this->Money . $change);
                        $tickets->TextoCentro('PDHN');
                        $nid = $dataClient[0]['NID'];
                        $tickets->PDF("Client/Interests", "$ticket-$currentDate", "$name-$nid");

                        $this->db->pdo->commit();
                        return $idClient;
                    }
                } catch (Throwable $th) {
                    echo $th->getMessage();
                }

                break;
        }
    }

    public function Tcustomer_interests($idClient)
    {
        $where = ' WHERE IdCustomer = :IdCustomer AND Canceled = :Canceled';
        $response1 = $this->db->Select1('*', 'tcustomer_interests', $where, array('IdCustomer' => $idClient, 'Canceled' => false));
        return $response1['results'];
    }

    public function GetPayments($date1, $date2, $paginador)
    {
        $idClient = Session::getSession('idClient');
        $where = ' WHERE IdClients = :IdClients';
        if (null != $date1 || null != $date2) {
            $date1 = strtotime(str_replace('/', '-', $date1));
            $date2 = strtotime(str_replace('/', '-', $date2));
            $fecha_actual = strtotime(date('d-m-Y', time()));
            if ($date1 == $date2 && $date1 == $fecha_actual && $fecha_actual == $date2) {
                return $this->db->Select5('*', 'tpayments_clients', $where, array('IdClients' => $idClient),"IdPayments",5);
            } else {
                if ($date1 < $date2) {
                    $response = $this->db->Select1('*', 'tpayments_clients', $where, array('IdClients' => $idClient));
                    $array = array();
                    $a = 0;
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
                    return array("results" =>array_reverse($array1));
                } else {
                    return 'La fecha final debe ser mayor a la fecha de inicio';
                }
            }
        } else {
            return $this->db->Select5('*', 'tpayments_clients', $where, array('IdClients' => $idClient),"IdPayments",5);
        }
    }

    public function DetailsDebt($idDebt)
    {
        $where = ' WHERE IdPayments = :IdPayments';
        $condition = 'tclients.IdClient=tpayments_clients.IdClients';
        $columns = 'tclients.IdClient,tclients.Name,tclients.LastName,tpayments_clients.IdPayments,tpayments_clients.Debt,tpayments_clients.Payment,tpayments_clients.Changes,tpayments_clients.CurrentDebt,tpayments_clients.Monthly,tpayments_clients.PreviousDebt,tpayments_clients.Date,tpayments_clients.Deadline,tpayments_clients.DateDebt,tpayments_clients.Ticket,tpayments_clients.User';
        $payments = $this->db->Select3($columns, 'tpayments_clients', 'tclients', $condition, $where, array('IdPayments' => $idDebt));
        return  $payments['results'][0];
    }

    public function GetTClientInterest($idClient)
    {
        $interestsCoutas = 0;
        $interests = 0.0;
        $where1 = ' WHERE IdCustomer = :IdClient AND Canceled = :Canceled';
        $response1 = $this->db->Select1('*', 'tcustomer_interests', $where1, array('IdClient' => $idClient, 'Canceled' => false));
        $where2 = ' WHERE IdClient = :IdClient';
        $response2 = $this->db->Select1('*', 'tcustomer_interests_reports', $where2, array('IdClient' => $idClient));
        if (is_array($response1) && is_array($response2)) {
            if (0 < count($response1['results'])) {
                foreach ($response1['results'] as $key => $value) {
                    $interestsCoutas++;
                    $interests += $value['Interests'];
                }
            }
            $response2 = $response2['results'];
            if (0 < count($response2)) {
                return array(
                    'Interests' => $interests,
                    'Fee' => $interestsCoutas,
                    'Payment' => $response2[0]['Payment'],
                    'InterestDate' => $response2[0]['InterestDate'],
                    'Change' => $response2[0]['Changes'],
                    'IdClient' => $idClient,
                );
            } else {
                return array(
                    'Interests' => $interests,
                    'Fee' => $interestsCoutas,
                    'Payment' => 0,
                    'InterestDate' => null,
                    'Change' => 0,
                    'IdClient' => $idClient,
                );
            }
        }
    }

    public function AmountFees($fees, $idClient)
    {
        $interests = 0;
        $where1 = ' WHERE IdCustomer = :IdClient';
        $response1 = $this->db->Select1('*', 'tcustomer_interests', $where1, array('IdClient' => $idClient));
        $response1 = $response1['results'];
        if (0 < count($response1)) {
            if (count($response1)  >= $fees && $fees <= count($response1)) {
                for ($i = 0; $i < $fees; $i++) {

                    $interests += $response1[$i]['Interests'];
                }
                return DecimalFormat::number_format($interests);
            } else {
                return 'Se sobrepasó de las cuotas a pagar';
            }
        } else {
            return 'El cliente no debe intereses';
        }
    }
    public function GetInterest($date1, $date2, $paginador)
    {
        $idClient = Session::getSession('idClient');
        $where = ' WHERE IdCustomer = :IdCustomer';
        if (null != $date1 || null != $date2) {
            $date1 = strtotime(str_replace('/', '-', $date1));
            $date2 = strtotime(str_replace('/', '-', $date2));
            $fecha_actual = strtotime(date('d-m-Y', time()));
            if ($date1 == $date2 && $date1 == $fecha_actual && $fecha_actual == $date2) {
                return $this->db->Select5('*', 'tpayments_customer_interest', $where, array('IdCustomer' => $idClient),"IdPaymentsInterest",5);
            } else {
                if ($date1 < $date2) {
                    $response = $this->db->Select1('*', 'tpayments_customer_interest', $where, array('IdCustomer' => $idClient));
                    $array = array();
                    $a = 0;
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
                    return array("results" =>array_reverse($array1));
                } else {
                    return 'La fecha final debe ser mayor a la fecha de inicio';
                }
            }
        } else {
            return $this->db->Select5('*', 'tpayments_customer_interest', $where, array('IdCustomer' => $idClient),"IdPaymentsInterest",5);
        }
    }
    public function DetailsInterest($idDebt)
    {
        $where = ' WHERE IdPaymentsInterest = :IdPaymentsInterest';
        $condition = 'tclients.IdClient=tpayments_customer_interest.IdCustomer';
        $columns = 'tclients.IdClient,tclients.Name,tclients.LastName,tpayments_customer_interest.IdPaymentsInterest,tpayments_customer_interest.Interests,tpayments_customer_interest.Payment,tpayments_customer_interest.Changes,tpayments_customer_interest.Fee,tpayments_customer_interest.Date,tpayments_customer_interest.Ticket,tpayments_customer_interest.IdUser,tpayments_customer_interest.User';
        $payments = $this->db->Select3($columns, 'tpayments_customer_interest', 'tclients', $condition, $where, array('IdPaymentsInterest' => $idDebt));
        return  $payments['results'][0];
    }
}
