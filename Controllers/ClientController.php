<?php

class ClientController extends Controllers
 {
    private $Money = null;

    public function __construct() {
        parent::__construct();
        $this->Money = Setting::$setting['TypeMoney'];
    }

    public function Register()
 {
        if ( null != Session::getSession( 'User' ) ) {
            $model1 = Session::getSession( 'model1' );
            $model2 = Session::getSession( 'model2' );
            if ( null != $model1 || null != $model2 ) {
                $array1 = unserialize( $model1 );
                $array2 = unserialize( $model2 );
                if ( is_array( $array1 ) && is_array( $array2 ) ) {
                    $model1 = $this->TClient( $array1 );
                    $model2 = $this->TClient( $array2 );
                    $this->view->Render( $this, 'register', $model1, $model2, null );
                } else {
                    $this->view->Render( $this, 'register', null, null, null );

                }
            } else {
                $this->view->Render( $this, 'register', $this->TClient( array() ), null, null );

            }
        } else {
            header( 'Location:'.URL );
        }

    }

    public function AddClient() {
        $user = Session::getSession( 'User' );
        if ( null != $user ) {
            if ( 'Admin' == $user['Role'] ) {
                $execute = true;
                $image = null;
                if ( empty( $_POST['nid'] ) ) {
                    $nid = 'Ingrese el nid';
                    $execute = false;
                }
                if ( empty( $_POST['name'] ) ) {
                    $name = 'Ingrese el nombre';
                    $execute = false;
                }
                if ( empty( $_POST['lastname'] ) ) {
                    $lastname = 'Ingrese el apellido';
                    $execute = false;
                }
                if ( empty( $_POST['email'] ) ) {
                    $email = 'Ingrese el email';
                    $execute = false;
                } else {
                    if ( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) ) {
                        $email = 'Ingrese un email valido';
                        $execute = false;
                    }
                }

                if ( empty( $_POST['phone'] ) ) {
                    $phone = 'Ingrese el telefono';
                    $execute = false;
                }
                if ( empty( $_POST['direction'] ) ) {
                    $direction = 'Ingrese la direccion';
                    $execute = false;
                }

                if ( isset( $_FILES['file'] ) ) {
                    $archivo =  $_FILES['file']['tmp_name'];
                    if ( $archivo != null ) {
                        $contents = file_get_contents( $archivo );

                        $image = base64_encode( $contents );
                    } else {
                        $model1 = Session::getSession( 'model1' );
                        if ( null != $model1 ) {
                            $array1 = unserialize( $model1 );
                            $image = $array1['Image'];
                        } else {
                            $img = file_get_contents( URL.RQ.'images/default.png' );

                            $image = base64_encode( $img );
                        }
                    }
                }
                $model1 = array(
                    'IdClient'=>$_POST['idclient'] ?? null,
                    'NID'=>$_POST['nid'] ?? null,
                    'Name'=>$_POST['name'] ?? null,
                    'LastName'=>$_POST['lastname'] ?? null,
                    'Email'=>$_POST['email'] ?? null,
                    'Phone'=>$_POST['phone'] ?? null,
                    'Direction'=>$_POST['direction'] ?? null,
                    'Image'=>$image ?? null,
                    'Credit'=>$_POST['credit'] ?? null
                );
                Session::setSession( 'model1', serialize( $model1 ) );
                if ( $execute ) {
                    $value = $this->model->AddClient(
                        $this->TClient( $model1 ),
                        $this->TReports_clients( array() )
                    );
                    if ( is_numeric( $value ) ) {
                        Session::setSession( 'model1', '' );
                        Session::setSession( 'model2', '' );
                        if ( $value == 0 ) {
                            header( 'Location: Client' );
                        } else {
                            header( 'Location: '.URL.'Client/Details/'.$value );
                        }

                    } else {
                        Session::setSession( 'model2', serialize( array(
                            'Credit'=>$value,
                        ) ) );
                        header( 'Location: Register' );
                    }

                } else {
                    Session::setSession( 'model2', serialize( array(
                        'NID'=>$nid ?? null,
                        'Name'=>$name ?? null,
                        'LastName'=>$lastname ?? null,
                        'Email'=>$email ?? null,
                        'Phone'=>$phone ?? null,
                        'Direction'=>$direction ?? null,
                        'Credit'=>$_POST['credit'] ?? null
                    ) ) );
                    header( 'Location: Register' );
                }

            } else {
                Session::setSession( 'model1', serialize( array() ) );
                Session::setSession( 'model2', serialize( array(
                    'Email'=>'No cuenta con los permisos requeridos para ejecutar esta acción',
                ) ) );
                header( 'Location: Register' );
            }
        }
    }

    public function Client( $page )
 {
        if ( null != Session::getSession( 'User' ) ) {
            $filter = ( isset( $_GET['filtrar'] ) ) ? $_GET['filtrar'] : '' ;
            $response = $this->model->GetClients( $this->paginador, $filter,
            $page, 1, 'Client/Client', URL );
            if ( is_array( $response ) ) {
                if ( 0 < count( $response['results'] ) ) {
                    $response = $response;
                } else {
                    $response = array(
                        'results' => null,
                        'pagi_info' => null,
                        'pagi_navegacion' => 'No hay datos que mostrar'
                    );
                }
            } else {
                $response = array(
                    'results' => null,
                    'pagi_info' => null,
                    'pagi_navegacion' => $response
                );
            }
            $this->view->Render( $this, 'client', $response, null, null );
        } else {
            header( 'Location:'.URL );
        }

    }

    public function Details( $id ) {
        if ( null != Session::getSession( 'User' ) ) {
            Session::setSession( 'idClient', $id );
            $response = $this->model->GetClients( null, $id,
            null, null, null, null );
            if ( is_array( $response ) ) {
                if ( 0 < count( $response['results'] ) ) {
                    $this->view->Render( $this, 'details', $response['results'], null, null );
                } else {
                    header( 'Location:'.URL.'Client/Client' );
                }
            } else {
                header( 'Location:'.URL.'Client/Client' );
            }
        } else {
            header( 'Location:'.URL );

        }
    }

    public function Reports() {
        if ( null != Session::getSession( 'User' ) ) {
            $idClient = $_GET['id'];
            Session::setSession( 'idClient', $idClient );
            $response = $this->model->GetTClientReport( $idClient );
            $response1 = $this->model->GetTClientInterest( $idClient );
            $payments = Session::getSession( 'payments' );
            if ( null != $payments ) {

                $payments = unserialize( $payments );
                Session::setSession( 'payments', null );
            } else {

                $this->GetPayments();
            }
            $interests = Session::getSession( 'interests' );
            if ( null != $interests ) {

                $interests = unserialize( $interests );
                Session::setSession( 'interests', null );
            } else {

                $this->GetPayments();
            }
            if ( is_array( $response )  && is_array( $response1 ) ) {
                if ( 0 <= count( $response['results'] ) || 0 <= count( $response1 ) ) {
                    $model3 = Session::getSession( 'model3' );
                    $response = $response['results'];
                    if ( null != $model3 ) {
                        $array3 = unserialize( $model3 );
                        if ( is_array( $array3 ) ) {
                            $model3 = $this->TReports_clients( $array3 );
                            $this->view->Render( $this, 'reports', array( $response, $response1 ), array( $payments, $this->Money, $interests ), $model3 );
                        } else {
                            $this->view->Render( $this, 'reports', array( $response, $response1 ), array( $payments, $this->Money, $interests ), $this->TReports_clients( array() ) );
                        }
                    } else {
                        $this->view->Render( $this, 'reports', array( $response, $response1 ), array( $payments, $this->Money, $interests ), $this->TReports_clients( array() ) );
                    }
                } else {
                    header( 'Location:'.URL.'Client/Details/'.Session::getSession( 'idClient' ) );
                }
            } else {
                header( 'Location:'.URL.'Client/Details/'.Session::getSession( 'idClient' ) );
            }
        } else {
            header( 'Location:'.URL );

        }
    }

    public function Payment() {
        $user = Session::getSession( 'User' );
        if ( null != $user ) {
            $value = $this->model->Payment( $_POST['radioOptions'],
            $_POST['payment'], $_POST['idClient'], $this->TReports_clients( array() )
            , $this->TPayments_clients( array() ), $user, $_POST['AmountFees'], $this->TPayments_Customer_Interest( array() ),
            $this->TCustomer_interests_reports( array() ) );
            if ( is_numeric( $value ) ) {
                Session::setSession( 'model3', '' );
               /* $response = $this->model->GetTClientReport( $value );
                $response1 = $this->model->GetTClientInterest( $value );
                $this->view->Render( $this, 'reports', array( $response, $response1 ), array( array(), $this->Money ), $this->TReports_clients( array() ) );*/
            } else {
                Session::setSession( 'model3', serialize( array(
                    'LastPayment'=>$value,
                ) ) );
                
            }
            header( 'Location: '.URL.'Client/Reports?id='.Session::getSession( 'idClient' ) );
        } else {
            header( 'Location:'.URL );

        }
    }

    public function GetPayments() {
        if ( empty( $_POST['date1'] ) || empty( $_POST['date2'] ) ) {
            $payments = $this->model->GetPayments( null, null, $this->paginador );
            Session::setSession( 'payments', serialize( $payments ) );
            $interests = $this->model->GetInterest( null, null, $this->paginador );
            Session::setSession( 'interests', serialize( $interests ) );
            header( 'Location: '.URL.'Client/Reports?id='.Session::getSession( 'idClient' ) );

        } else {

            $payments = $this->model->GetPayments( $_POST['date1'], $_POST['date2'], $this->paginador );
            Session::setSession( 'payments', serialize( array( 'results' => $payments ) ) );
            $interests = $this->model->GetInterest( $_POST['date1'], $_POST['date2'], $this->paginador );
            Session::setSession( 'interests', serialize( array( 'results' => $interests ) ) );
            header( 'Location: '.URL.'Client/Reports?id='.Session::getSession( 'idClient' ) );

        }

    }

    public function DetailsDebt() {
        if ( null != Session::getSession( 'User' ) ) {
            $payments = $this->model->DetailsDebt( $_GET['idDebt'] );
            Session::setSession( 'Ticket', serialize( $payments ) );
            $this->view->Render( $this, 'detailsdebt', $payments, $this->Money, null );
        } else {
            header( 'Location:'.URL );
        }

    }

    public function TicketDebt() {
        $ticket = Session::getSession( 'Ticket' );
        $ticket = unserialize( $ticket );
        $name = $ticket['Name'].' '.$ticket['LastName'];
        $tickets = new Ticket();
        $tickets->TextoCentro( 'Sistema de ventas PDHN' );
        // imprime en el centro
        $tickets->TextoIzquierda( 'Direccion' );
        $tickets->TextoIzquierda( 'La Ceiba, Atlantidad' );
        $tickets->TextoIzquierda( 'Tel 658912146' );
        $tickets->LineasGuion();
        $tickets->TextoCentro( 'FACTURA' );
        // imprime en el centro
        $tickets->LineasGuion();
        $tickets->TextoIzquierda( 'Factura:'.$ticket['Ticket'] );
        $tickets->TextoIzquierda( "Cliente: $name" );
        $tickets->TextoIzquierda( 'Fecha:'.$ticket['Date'] );
        $tickets->TextoIzquierda( 'Usuario:'.$ticket['User'] );
        $tickets->LineasGuion();
        $tickets->TextoCentro( 'Su cretito '.$this->Money.number_format( $ticket['Debt'] ) );
        $tickets->TextoExtremo( 'Cuotas por meses:', $this->Money.number_format( $ticket['Monthly'] ) );
        $tickets->TextoExtremo( 'Deuda anterior:', $this->Money.number_format( $ticket['PreviousDebt'] ) );
        $tickets->TextoExtremo( 'Pago:', $this->Money.number_format( $ticket['Payment'] ) );
        $tickets->TextoExtremo( 'Cambio:', $this->Money.number_format( $ticket['Changes'] ) );
        $tickets->TextoExtremo( 'Deuda actual:', $this->Money.number_format( $ticket['CurrentDebt'] ) );
        $tickets->TextoExtremo( 'Próximo pago:', $ticket['Deadline'] );
        $tickets->TextoCentro( 'PDHN' );
        $tickets->PDFdownload($ticket['Ticket']."-".$ticket['Date'],$name);
    }

    public function Fees() {

        echo  $_POST['fees'] == '0' ? '' : $this->model->AmountFees( $_POST['fees'],  $_POST['idClient'] );

    }

    public function DetailsInterest() {
        if ( null != Session::getSession( 'User' ) ) {
            $payments = $this->model->DetailsInterest( $_GET['idInterest'] );
            Session::setSession( 'TicketInterest', serialize( $payments ) );
            $this->view->Render( $this, 'detailsinterest', $payments, $this->Money, null );
        } else {
            header( 'Location:'.URL );
        }

    }

    public function TicketInterest() {
        $ticket = Session::getSession( 'TicketInterest' );
        $ticket = unserialize( $ticket );
        $name = $ticket['Name'].' '.$ticket['LastName'];
        $tickets = new Ticket();
        $tickets->TextoCentro( 'Sistema de ventas PDHN' );
        // imprime en el centro
        $tickets->TextoIzquierda( 'Direccion' );
        $tickets->TextoIzquierda( 'La Ceiba, Atlantidad' );
        $tickets->TextoIzquierda( 'Tel 658912146' );
        $tickets->LineasGuion();
        $tickets->TextoCentro( 'FACTURA' );
        // imprime en el centro
        $tickets->LineasGuion();
        $tickets->TextoIzquierda( 'Factura:'.$ticket['Ticket'] );
        $tickets->TextoIzquierda( "Cliente: $name");
        $tickets->TextoIzquierda( 'Fecha:'.$ticket['Date'] );
        $tickets->TextoIzquierda( 'Usuario:'.$ticket['User'] );
        $tickets->LineasGuion();
        $tickets->TextoCentro( 'Intereses '.$this->Money.number_format( $ticket['Interests'] ) );
        $tickets->TextoExtremo( 'Pago:', $this->Money.number_format( $ticket['Payment'] ) );
        $tickets->TextoExtremo( 'Cambio:', $this->Money.number_format( $ticket['Changes'] ) );

        $tickets->TextoCentro( 'PDHN' );
        $tickets->PDFdownload($ticket['Ticket']."-".$ticket['Date'],$name);
    }

    public function Cancel() {
        Session::setSession( 'model1', '' );
        Session::setSession( 'model2', '' );
        header( 'Location: Register' );
    }
}

?>