<?php
class Shopping_model extends Connection
{
    public function __construct()
    {
        parent::__construct();
        $this->Money = Setting::$setting['TypeMoney'];
    }
    public function GetProviders($paginador, $filter, $page, $register, $method, $url)
    {
        if ($paginador != null) {
            $where = ' WHERE Provider LIKE :Provider OR Email LIKE :Email';
            $array = array(
                'Provider' => '%' . $filter . '%',
                'Email' => '%' . $filter . '%'
            );
            return $paginador->paginador('IdProvider,Provider', 'tproviders', $method, $register, $page, $where, $array, $url);
        } else {
            $where = ' WHERE IdProvider = :IdProvider';
            return  $this->db->Select1('*', 'tproviders', $where, array('IdProvider' => $filter));
        }
    }
    public function AddShopping($model1, $user, $idTemporary)
    {
        try {
            $value = false;
            $currentDate = date('Y-m-d');
            $this->db->pdo->beginTransaction();
            $response = $this->GetProviders(null,  $model1->IdProvider, null, null, null, null);
            if (0 < count($response["results"])) {
                $dataProvider = $response["results"][0];
                $where = ' WHERE IdProvider = :IdProvider AND IdUser = :IdUser';
                $data = $this->db->Select1('*', 'temporary_shopping', $where, array(
                    'IdProvider' => $dataProvider["IdProvider"],
                    'IdUser' => $user['IdUser']
                ));
                if ($idTemporary == 0) {
                    $value = true;
                } else {
                    if (0 == count($data["results"])) {
                        $value = false;
                        return "Finalizar la compra del proveedor en lista";
                    } else {
                        $value = true;
                    }
                }

                if ($value) {
                    $model1->IdTemporary = $idTemporary;
                    $Amount = $model1->Price * $model1->Quantity;
                    $model1->Amount = $Amount;
                    $model1->IdUser = $user['IdUser'];
                    $model1->Date = $currentDate;
                    if ($idTemporary == 0) {
                        $query1 = 'INSERT INTO temporary_shopping (IdTemporary,Description,Quantity,Price,Amount,IdProvider,Provider,IdUser,Image,Date) VALUES (:IdTemporary,:Description,:Quantity,:Price,:Amount,:IdProvider,:Provider, :IdUser,:Image,:Date)';
                    } else {
                        $query1 =  'UPDATE temporary_shopping SET IdTemporary = :IdTemporary,Description = :Description,Quantity = :Quantity,Price = :Price,Amount = :Amount,IdProvider = :IdProvider,Provider = :Provider,IdUser = :IdUser,Image = :Image,Date = :Date WHERE IdTemporary = ' . $idTemporary;
                    }
                    $sth = $this->db->pdo->prepare($query1);
                    $sth->execute((array)$model1);
                    $this->db->pdo->commit();
                    return $idTemporary;
                }
            } else {
                return "El proveedor no esta registrado";
            }
        } catch (Throwable $th) {
            $this->db->pdo->rollBack();
            echo $th->getMessage();
        }
    }
    public function GetTemporary_shopping(
        $paginador,
        $filter,
        $page,
        $register,
        $method,
        $url,
        $user
    ) {
        if ($paginador != null) {
            $where = ' WHERE Description LIKE :Description AND IdUser LIKE :IdUser';
            $array = array(
                'Description' => '%' . $filter . '%',
                'IdUser' => '%' . $user["IdUser"] . '%',
            );
            return $paginador->paginador('*', 'temporary_shopping', $method, $register, $page, $where, $array, $url);
        } else {
            $where = ' WHERE IdTemporary = :IdTemporary';
            return  $this->db->Select1('*', 'temporary_shopping', $where, array('IdTemporary' => $filter));
        }
    }
    public function GetTotalAmount($user)
    {
        $amount = 0.0;
        $where = ' WHERE IdUser LIKE :IdUser';
        $listTemporary = $this->db->Select1('Amount', 'temporary_shopping', $where, array('IdUser' => $user["IdUser"]));
        if (is_array($listTemporary["results"])) {
            foreach ($listTemporary["results"] as $key => $value) {
                $amount += $value["Amount"];
            }
        }
        return $amount;
    }
    public function DeleteShopping($idTemporary)
    {
        $where = " WHERE IdTemporary = :IdTemporary";

        return $this->db->delete('temporary_shopping', $where, array('IdTemporary' => $idTemporary));
    }
    public function Payments($payments, $credit, $user, $model1, $model2, $model3)
    {
        try {
            $this->db->pdo->beginTransaction();
            $where = ' WHERE IdUser LIKE :IdUser';
            $listTemporary = $this->db->Select1('*', 'temporary_shopping', $where, array('IdUser' => $user["IdUser"]));
            if (is_array($listTemporary["results"])) {
                $Temporary = $listTemporary["results"];
                if (0 < count($Temporary)) {
                    $_ticket = null;
                    $currentDate = date('Y-m-d');
                    $dateNow = date('Y-m-d');
                    $year = date('Y');
                    $_change = 0.0;
                    $_debt = 0.0;
                    $_currentDebt = 0.0;
                    $name = $user["Name"] . " " . $user["LastName"];
                    $amount = $this->GetTotalAmount($user);
                    $debt =  $amount - $payments;
                    $where = ' WHERE IdProvider LIKE :IdProvider AND YEAR(Date) LIKE :Date';
                    $dataShoppin = $this->db->Select1(
                        '*',
                        'tshopping',
                        $where,
                        array(
                            'IdProvider' => $Temporary[0]["IdProvider"],
                            'Date' =>  $year
                        )
                    );
                    
                    $Shoppin = array();
                    if (is_array($dataShoppin["results"])) {
                        $Shoppin = $dataShoppin["results"];
                    }
                    $ticket = count($Shoppin) > 0 ? Codes::codesTickets($Shoppin[0]['Ticket']) : "0000000001";
                    $dataProvider =  $this->GetTProviderReport($Temporary[0]["IdProvider"]);
                    if (is_array($dataProvider["results"])) {
                        $dataProvider = $dataProvider["results"];
                        if (0 < count($dataProvider)) {
                            if ($amount < $payments) {
                                $_change =  (float) str_replace("-", "", $debt);
                                $_currentDebt = $dataProvider[0]["CurrentDebt"];
                                $_debt = $dataProvider[0]["Debt"];
                                $currentDate  = $dataProvider[0]["DateDebt"];
                            } else if ($amount > $payments) {
                                $_change = $dataProvider[0]["Changes"];
                                $_currentDebt = $debt + (float)$dataProvider[0]["CurrentDebt"];
                                $_debt = $debt + (float)$dataProvider[0]["CurrentDebt"];
                            } else if ($amount == $payments) {
                                $_debt = $dataProvider[0]["CurrentDebt"];
                                $currentDate  = $dataProvider[0]["DateDebt"];
                                $_change = $dataProvider[0]["Changes"];
                                $_currentDebt = $dataProvider[0]["CurrentDebt"];
                            }
                            $model1->IdReport = $dataProvider[0]["IdReport"];
                            $model1->Debt = $_debt;
                            $model1->Monthly = $dataProvider[0]["Monthly"];
                            $model1->Changes = $_change;
                            $model1->LastPayment = $dataProvider[0]["LastPayment"];
                            $model1->DatePayment = $dataProvider[0]["DatePayment"];
                            $model1->CurrentDebt = $_currentDebt;
                            $model1->DateDebt = $currentDate;
                            $model1->Ticket = $dataProvider[0]["Ticket"];
                            $model1->IdProviders = $dataProvider[0]["IdProviders"];
                            $model1->Agreement = $dataProvider[0]["Agreement"];

                            $query1 =  'UPDATE treports_providers SET IdReport = :IdReport,Debt = :Debt,Monthly = :Monthly,Changes = :Changes,LastPayment = :LastPayment,DatePayment = :DatePayment,CurrentDebt = :CurrentDebt,DateDebt = :DateDebt,Ticket = :Ticket,IdProviders = :IdProviders,Agreement = :Agreement WHERE IdReport = ' . $dataProvider[0]["IdReport"];

                            $sth = $this->db->pdo->prepare($query1);
                            $sth->execute((array)$model1);
                            $products = 0;
                            foreach ($Temporary as $key => $value){
                                $model2->Description = $value["Description"];
                                $model2->Quantity = $value["Quantity"];
                                $model2->Price = $value["Price"];
                                $model2->Amount = $value["Amount"];
                                //$model2->Provider = $value["Provider"];
                                $model2->IdProvider = $value["IdProvider"];
                                $model2->IdUser = $value["IdUser"];
                                $model2->Image = $value["Image"];
                                $model2->Date = $dateNow;
                                $model2->Ticket = $ticket;

                                $query1 = 'INSERT INTO tshopping (IdShopping,Description,Quantity,Price,Amount,IdProvider,IdUser,Image,Date,Ticket) VALUES (:IdShopping,:Description,:Quantity,:Price,:Amount,:IdProvider, :IdUser,:Image,:Date,:Ticket)';

                                $sth = $this->db->pdo->prepare($query1);
                                $sth->execute((array)$model2);
                                $idShopping = $this->db->pdo->lastInsertId();

                                $query2 = 'INSERT INTO temporary_product (IdTemporary,IdShopping,IdUser) VALUES (:IdTemporary,:IdShopping,:IdUser)';

                               $sth = $this->db->pdo->prepare($query2);
                                $sth->execute(array(
                                    'IdTemporary' => 0,
                                    'IdShopping' =>  $idShopping,
                                    'IdUser' =>  $value["IdUser"]
                                ));
                                $products += $value["Quantity"];
                            }
                            $_change = $amount == $payments ? 0 : $_change;
                            $model3->Ticket = $ticket;
                            $model3->Products = $products;
                            $model3->Credit = $credit ? $debt : 0;
                            $model3->Payment = $payments;
                            $model3->Debt = $amount;
                            $model3->IdProvider = $Temporary[0]["IdProvider"];
                            $model3->Changes = $_change;
                            $model3->Date = $dateNow;

                            $query2 = 'INSERT INTO treports_shopping (IdReport,Ticket,Products,Credit,Payment,Debt,IdProvider,Changes,Date) VALUES (:IdReport,:Ticket,:Products,:Credit,:Payment,:Debt,:IdProvider,:Changes,:Date)';

                            $sth = $this->db->pdo->prepare($query2);
                            $sth->execute((array)$model3);

                            $tickets = new Ticket();
                            $tickets->TextoCentro('Sistema de ventas PDHN');
                            $tickets->TextoIzquierda('Direccion');
                            $tickets->TextoIzquierda('La Ceiba, Atlantidad');
                            $tickets->TextoIzquierda('Tel 658912146');
                            $tickets->LineasGuion();
                            $tickets->TextoCentro('FACTURA');
                            $tickets->LineasGuion();
                            $tickets->TextoIzquierda("Factura: $ticket");
                            $tickets->TextoIzquierda('Proveedor:' . $Temporary[0]['Provider']);
                            $tickets->TextoIzquierda("Fecha: $dateNow");
                            $tickets->TextoIzquierda("Usuario: $name");
                            $tickets->LineasGuion();
                            $data = $credit ? "Productos al credito" : "Productos al contado";
                            $tickets->TextoCentro($data);
                            $tickets->AgregarArticulo("Producto", "cant.", "Precio");
                            $amount = 0;
                            foreach ($Temporary as $key => $value){
                                $amount += $value["Amount"];
                                $price = $this->Money . DecimalFormat::number_format($value["Price"]);
                                $tickets->AgregarArticulo($value["Description"], $value["Quantity"], $price);
                            }
                            $tickets->LineasGuion();
                            $tickets->TextoCentro("Deuda y pago generado");
                            $tickets->AgregarTotales("Total a pagar", $amount, $this->Money);
                            $tickets->AgregarTotales("Pago:", $payments, $this->Money);
                            if ($credit){
                                $tickets->AgregarTotales("Importe faltante", $debt, $this->Money);
                            }else{
                                if ($payments >=  $amount){
                                    $tickets->AgregarTotales("Cambio:",  $_change, $this->Money);
                                }
                            }
                            $tickets->TextoCentro("PDHN");
                            $name = $Temporary[0]['Provider']."-".$Temporary[0]['IdProvider'];
                            $tickets->PDF("Provider/Shopping", "$ticket-$dateNow", $name);
                            /*foreach ($Temporary as $key => $value){
                                $this-> DeleteShopping($value["IdTemporary"]);
                            }*/
                        }
                    }
                }
            }
            $this->db->pdo->commit();
            return true;
        } catch (Throwable $th) {
            $this->db->pdo->rollBack();
            return $th->getMessage();
        }
    }
    public function GetTProviderReport($idProvider)
    {
        $where = ' WHERE IdProvider = :IdProvider';
        $condition = 'tproviders.IdProvider = treports_providers.IdProviders';
        return  $this->db->Select3('*', 'tproviders', 'treports_providers', $condition, $where, array('IdProvider' => $idProvider));
    }
    public function GetShoppings( $paginador, $filter, $page, $register, $method, $url ){
        if ( $paginador != null ){
            $where = ' WHERE Description LIKE :Description OR Ticket LIKE :Ticket';
            $array = array(
                'Description' => '%'.$filter.'%',
                'Ticket' => '%'.$filter.'%'
            );
            return $paginador->paginador( '*', 'tshopping', $method, $register, $page, $where, $array, $url );
        }else{
            $where = ' WHERE IdShopping = :IdShopping';
            $condition = 'tshopping.IdProvider = tproviders.IdProvider';
            $columns = "tshopping.IdShopping,tshopping.Description,tshopping.Quantity,tshopping.Price,tshopping.Amount,tshopping.Image,tshopping.Ticket,tshopping.Date,tproviders.Provider";
            return  $this->db->Select3( $columns, 'tshopping', 'tproviders',$condition,$where, array( 'IdShopping' => $filter ) );
        }
    }
}
