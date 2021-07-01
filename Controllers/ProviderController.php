<?php

class ProviderController extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        $this->Money = Setting::$setting['TypeMoney'];
    }

    public function Register()
    {
        if (null != Session::getSession('User')) {
            $model1 = Session::getSession('model1');
            $model2 = Session::getSession('model2');
            if (null != $model1 || null != $model2) {
                $array1 = unserialize($model1);
                $array2 = unserialize($model2);
                if (is_array($array1) && is_array($array2)) {
                    $model1 = $this->TProvider($array1);
                    $model2 = $this->TProvider($array2);
                    $this->view->Render($this, 'register', $model1, $model2, null);
                } else {
                    $this->view->Render($this, 'register', $this->TProvider(array()), null, null);
                }
            } else {
                $this->view->Render($this, 'register', $this->TProvider(array()), null, null);
            }
        } else {
            header('Location:' . URL);
        }
    }

    public function AddProvider()
    {
        $user = Session::getSession('User');
        if (null != $user) {
            if ('Admin' == $user['Role']) {
                $execute = true;
                $image = null;
                if (empty($_POST['provider'])) {
                    $provider = 'Ingrese el proveedor';
                    $execute = false;
                }
                if (empty($_POST['phone'])) {
                    $phone = 'Ingrese el telefono';
                    $execute = false;
                }
                if (empty($_POST['direction'])) {
                    $direction = 'Ingrese la direccion';
                    $execute = false;
                }
                if (empty($_POST['email'])) {
                    $email = 'Ingrese el email';
                    $execute = false;
                } else {
                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        $email = 'Ingrese un email valido';
                        $execute = false;
                    }
                }
                if (isset($_FILES['file'])) {
                    $archivo =  $_FILES['file']['tmp_name'];
                    if ($archivo != null) {
                        $contents = file_get_contents($archivo);

                        $image = base64_encode($contents);
                    } else {
                        $model1 = Session::getSession('model1');
                        if (null != $model1) {
                            $array1 = unserialize($model1);
                            $image = $array1['Image'];
                        } else {
                            $img = file_get_contents(URL . RQ . 'images/default.png');

                            $image = base64_encode($img);
                        }
                    }
                }
                $model1 = array(
                    'IdProvider' => $_POST['idProvider'] ?? null,
                    'Provider' => $_POST['provider'] ?? null,
                    'Email' => $_POST['email'] ?? null,
                    'Phone' => $_POST['phone'] ?? null,
                    'Direction' => $_POST['direction'] ?? null,
                    'Image' => $image ?? null
                );
                Session::setSession('model1', serialize($model1));
                if ($execute) {
                    $value = $this->model->AddProvider(
                        $this->TProvider($model1),
                        $this->TReports_provider(array())
                    );
                    if (is_numeric($value)) {
                        Session::setSession('model1', '');
                        Session::setSession('model2', '');
                        if ($value == 0) {
                            header('Location: Provider');
                        } else {
                            header('Location: ' . URL . 'Provider/Details/' . $value);
                        }
                    } else {
                        Session::setSession('model2', serialize(array(
                            'Email' => $value,
                        )));
                        header('Location: Register');
                    }
                } else {
                    Session::setSession('model2', serialize(array(
                        'Provider' => $provider ?? null,
                        'Email' => $email ?? null,
                        'Phone' => $phone ?? null,
                        'Direction' => $direction ?? null
                    )));
                    header('Location: Register');
                }
            } else {
                Session::setSession('model1', serialize(array()));
                Session::setSession('model2', serialize(array(
                    'Email' => 'No cuenta con los permisos requeridos para ejecutar esta acción',
                )));
                header('Location: Register');
            }
        } else {
            header('Location:' . URL);
        }
    }

    public function Provider($page)
    {
        if (null != Session::getSession('User')) {
            $filter = (isset($_GET['filtrar'])) ? $_GET['filtrar'] : '';
            $response = $this->model->GetProviders(
                $this->paginador,
                $filter,
                $page,
                2,
                'Provider/Provider',
                URL
            );
            if (is_array($response)) {
                if (0 == count($response['results'])) {

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
            $this->view->Render($this, 'provider', $response, null, null);
        } else {
            header('Location:' . URL);
        }
    }

    public function Details($id)
    {
        if (null != Session::getSession('User')) {
            Session::setSession('idProvider', $id);
            $response = $this->model->GetProviders(
                null,
                $id,
                null,
                null,
                null,
                null
            );
            if (is_array($response)) {
                if (0 < count($response['results'])) {
                    $this->view->Render($this, 'details', $response['results'], null, null);
                } else {
                    header('Location:' . URL . 'Provider/Provider');
                }
            } else {
                header('Location:' . URL . 'Provider/Provider');
            }
        } else {
            header('Location:' . URL);
        }
    }

    public function Reports()
    {
        if (null != Session::getSession('User')) {
            $idProvider = $_GET['id'];
            Session::setSession('idProvider', $idProvider);
            $response = $this->model->GetTProviderReport($idProvider);
            $payments = Session::getSession('payments');
            if (null != $payments) {
                $payments = unserialize($payments);
                Session::setSession('payments', null);
            } else {
                $this->GetPayments();
            }

            if (is_array($response)) {
                if (0 <= count($response['results'])) {
                    $response = $response['results'];
                    $model3 = Session::getSession('model3');
                    if (null != $model3) {
                        $array3 = unserialize($model3);
                        if (is_array($array3)) {
                            $model3 = $this->TReports_provider($array3);
                            $this->view->Render($this, 'reports', array($response, $this->Money), $payments, $model3);
                        } else {
                            $this->view->Render($this, 'reports', array($response, $this->Money), $payments,  $this->TReports_provider(array()));
                        }
                    } else {
                        $this->view->Render($this, 'reports', array($response, $this->Money), $payments,  $this->TReports_provider(array()));
                    }
                } else {
                    header('Location:' . URL . 'Provider/Details/' . Session::getSession('idProvider'));
                }
            } else {
                header('Location:' . URL . 'Provider/Details/' . Session::getSession('idProvider'));
            }
        }
    }
    public function Payment()
    {
        $user = Session::getSession('User');
        if (null != $user) {
            $value = $this->model->Payment(
                $_POST['SetSection'],
                $_POST['ProviderFees'],
                $_POST['payment'],
                $_POST['idProvider'],
                $this->TReports_provider(array()),
                $user,
                $this->TPayments_providers(array()),
                $_POST['RadioOptions1'] ?? null
            );
            if (is_numeric($value)) {
                switch ($value) {
                    case 2:
                        header( 'Location: '.URL.'Provider/Reports?id='.Session::getSession( 'idProvider' ) );
                        break;
                    
                    default:
                        # code...
                        break;
                }
            } else {
                Session::setSession('model3', serialize(array(
                    'LastPayment' => $value,
                )));
                //header( 'Location: '.URL.'Provider/Reports?id='.Session::getSession( 'idProvider' ) );
            }
        } else {
            header('Location:' . URL);
        }
    }
    public function GetPayments()
    {
        $payments = null;
        if (empty($_POST['date1']) || empty($_POST['date2'])) {
            $payments = $this->model->GetPayments(null, null, $this->paginador);
        } else {
            $payments = $this->model->GetPayments($_POST['date1'], $_POST['date2'], $this->paginador);
        }
        Session::setSession('payments', serialize($payments));
        header('Location: ' . URL . 'Provider/Reports?id=' . Session::getSession('idProvider'));
    }
    public function DetailsDebt()
    {
        if (null != Session::getSession('User')) {
            $payments = $this->model->DetailsDebt($_GET['idDebt']);
            $provider = $this->model->GetTProviderReport($payments["IdProvider"]);
            if (is_array($payments)) {
                Session::setSession('Ticket', serialize($payments));
            }
            $this->view->Render($this, 'detailsdebt', $payments, $this->Money, $provider['results']);
        } else {
            header('Location:' . URL);
        }
    }
    public function TicketDebt()
    {
        $ticket = Session::getSession('Ticket');
        $ticket = unserialize($ticket);
        $debt = $ticket['PreviousDebt'] - $ticket['CurrentDebt'];
        $fees = $debt / $ticket['Monthly'];
        $provider = $this->model->GetTProviderReport($ticket["IdProvider"]);
        $provider = $provider['results'];
        $tickets = new Ticket();
        $tickets->TextoCentro('Sistema de ventas PDHN');
        // imprime en el centro
        $tickets->TextoIzquierda('Direccion');
        $tickets->TextoIzquierda('La Ceiba, Atlantidad');
        $tickets->TextoIzquierda('Tel 658912146');
        $tickets->LineasGuion();
        $tickets->TextoCentro('FACTURA');
        $tickets->LineasGuion();
        $tickets->TextoIzquierda('Factura:' . $ticket['Ticket']);
        $tickets->TextoIzquierda('Proveedor:' . $provider[0]['Provider']);
        $tickets->TextoIzquierda('Fecha:' . $ticket['Date']);
        $tickets->TextoIzquierda('Usuario:' . $ticket['Name'] . ' ' . $ticket['LastName']);
        $tickets->LineasGuion();
        $tickets->TextoCentro('Cretito ' . $this->Money . DecimalFormat::number_format($ticket['Debt']));
        $tickets->LineasGuion();
        $agreement = $ticket["Agreement"] == 'Q' ? 'Cuotas quincenales:': 'Cuotas por meses:';
        $tickets->TextoExtremo($agreement, $this->Money . DecimalFormat::number_format($ticket['Monthly']));
        $tickets->TextoExtremo('Numero de cuotas pagadas:', $fees);
        $tickets->TextoExtremo('Total a pagar:', $this->Money . DecimalFormat::number_format($debt));
        $tickets->LineasGuion();
        $tickets->TextoExtremo('Deuda anterior:', $this->Money . DecimalFormat::number_format($ticket['PreviousDebt']));
        $tickets->TextoExtremo('Pago:', $this->Money . DecimalFormat::number_format($ticket['Payment']));
        $tickets->TextoExtremo('Cambio:', $this->Money . DecimalFormat::number_format($ticket['Changes']));
        $tickets->TextoExtremo('Deuda actual:', $this->Money . DecimalFormat::number_format($ticket['CurrentDebt']));
        $tickets->TextoCentro('PDHN');
        $tickets->PDFdownload($ticket['Ticket']."-".$ticket['Date'],$provider[0]['Provider']);
    }
}
