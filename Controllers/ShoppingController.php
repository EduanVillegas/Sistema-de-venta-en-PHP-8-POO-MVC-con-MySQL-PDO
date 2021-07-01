<?php
class ShoppingController extends Controllers
{
    private $Money = null;
    public function __construct()
    {
        parent::__construct();
        $this->Money = Setting::$setting['TypeMoney'];
    }
    public function AddShoppings($page)
    {
        $user = Session::getSession('User');
        if (null != $user) {
            Session::setSession('page', $page);
            $model1 = Session::getSession('model1');
            $model2 = Session::getSession('model2');
            $filter = (isset($_POST['search'])) ? $_POST['search'] : '';
            $response = $this->model->GetTemporary_shopping(
                $this->paginador,
                $filter,
                $page,
                1,
                'Shopping/AddShoppings',
                URL,
                $user
            );
            $id = $_GET["IdTemporary"] ?? 0;
            $productData = $this->model->GetTemporary_shopping(null, $id, null, null, null, null, null);
            if (is_array($productData["results"])) {
                if (0 < count($productData['results'])) {
                    $model1 =  serialize($productData['results'][0]);
                    $model2 =  serialize(array());
                    Session::setSession('model1', $model1);
                    Session::setSession('model2', $model2);
                }
            }
            $amount = $this->model->GetTotalAmount($user);
            if (null != $model1 || null != $model2) {
                $array1 = unserialize($model1);
                $array2 = unserialize($model2);
                if (is_array($array1) && is_array($array2)) {
                    $model1 = $this->Temporary_shopping($array1);
                    $model2 = $this->TShopping($array2);
                    $this->view->Render($this, "addshopping", $model1, $model2, array($this->Money, $response, $amount, $page));
                } else {
                    $this->view->Render($this, 'addshopping', $this->Temporary_shopping(array()), $this->TShopping(array()), array($this->Money, $response, $amount, $page));
                }
            } else {
                $this->view->Render($this, 'addshopping', $this->Temporary_shopping(array()), $this->TShopping(array()), array($this->Money, $response, $amount, $page));
            }
        } else {
            header('Location:' . URL);
        }
    }
    public function AddShopping()
    {
        $user = Session::getSession('User');
        if (null != $user) {
            if ('Admin' == $user['Role']) {
                $execute = true;
                $image = null;
                if (empty($_POST['Description'])) {
                    $description = 'Ingrese la description';
                    $execute = false;
                }
                if (empty($_POST['Quantity'])) {
                    $quantity = 'Ingrese la cantidad';
                    $execute = false;
                }
                if (empty($_POST['Price'])) {
                    $price = 'Ingrese el precio';
                    $execute = false;
                } else {
                    if (!is_numeric($_POST['Price'])) {
                        $price = 'Ingresa un precio válido';
                        $execute = false;
                    }
                }
                if (empty($_POST['Provider'])) {
                    $provider = 'Ingrese el proveedor';
                    $execute = false;
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
                            $img = file_get_contents(URL . RQ . 'images/icons/add_shopping.png');
                            $image = base64_encode($img);
                        }
                    }
                }
                $model1 = array(
                    'IdTemporary' => $_POST['IdTemporary'] ?? null,
                    'Description' => $_POST['Description'] ?? null,
                    'Quantity' => $_POST['Quantity'] ?? null,
                    'Price' => $_POST['Price'] ?? null,
                    'Provider' => $_POST['Provider'] ?? null,
                    'IdProvider' => $_POST['listProvider'] ?? null,
                    'Image' => $image ?? null,
                );
                Session::setSession('model1', serialize($model1));
                if ($execute) {
                    $value = $this->model->AddShopping(
                        $this->Temporary_shopping($model1),
                        $user,
                        $_POST['IdTemporary']
                    );
                    if (is_numeric($value)) {
                        Session::setSession('model1', '');
                        Session::setSession('model2', '');
                        header('Location: AddShoppings/' . Session::getSession('page') . '?IdTemporary=0');
                    } else {
                        Session::setSession('model2', serialize(array(
                            'Ticket' => $value,
                        )));
                        header('Location: AddShoppings/' . Session::getSession('page'));
                    }
                } else {
                    Session::setSession('model2', serialize(array(
                        'Description' => $description ?? null,
                        'Quantity' => $quantity ?? null,
                        'Price' => $price ?? null,
                        'Provider' => $provider ?? null,
                    )));
                    header('Location: AddShoppings');
                }
            } else {
                Session::setSession('model1', serialize(array()));
                Session::setSession('model2', serialize(array(
                    'Provider' => 'No cuenta con los permisos requeridos para ejecutar esta acción',
                )));
                header('Location: AddShoppings');
            }
        } else {
            header('Location:' . URL);
        }
    }
    public function GetProvider()
    {
        $filter = (isset($_POST['provider'])) ? $_POST['provider'] : '';
        $response = $this->model->GetProviders(
            $this->paginador,
            $filter,
            1,
            10,
            'Shopping/GetProvider',
            URL
        );
        if (is_array($response["results"])) {
            $dataFilter = "";
            foreach ($response["results"] as $key => $value) {
                $dataFilter .= "<option value='" . $value["IdProvider"] . "'>" . $value["Provider"] . "</option>";
            }
            echo json_encode(array(
                "dataFilter" => $dataFilter,
            ));
        }
    }
    public function DeleteShopping()
    {
        $response = $this->model->DeleteShopping($_POST['delete']);
        if (!is_bool($response)) {
            Session::setSession('model2', serialize(array(
                'Ticket' => $response,
            )));
        }
        $this->Cancel();
    }
    public function Payments()
    {
        $user = Session::getSession('User');
        if (null != $user) {
            $credit = (isset($_POST['check'])) ? true : false;
            $payments = (isset($_POST['Payments'])) ? $_POST['Payments'] : 0.0;
            $response = $this->model->Payments(
                $payments,
                $credit,
                $user,
                $this->TReports_provider(array()),
                $this->TShopping(array()),
                $this->TReports_shopping(array())
            );
            if (!is_bool($response)) {
                Session::setSession('model2', serialize(array(
                    'Ticket' => $response,
                )));
            }
            header('Location: AddShoppings/' . Session::getSession('page'));
        }
    }
    public function Shoppings($page){
        $user = Session::getSession('User');
        if (null != $user){
            $filter = (isset($_GET['filtrar'])) ? $_GET['filtrar'] : '';
            $response = $this->model->GetShoppings(
                $this->paginador,
                $filter,
                $page,
                10,
                'Shopping/Shoppings',
                URL
            );
            if (is_array($response)){
                if (0 == count($response['results'])){
                    $response = array(
                        'results' => null,
                        'pagi_info' => null,
                        'pagi_navegacion' => 'No hay datos que mostrar'
                    );
                }
            }else{
                $response = array(
                    'results' => null,
                    'pagi_info' => null,
                    'pagi_navegacion' => $response
                );
            }
            $this->view->Render($this,"shoppings",$response,$this->Money,null);
        }else{
            header('Location:' . URL);
        }
    }
    public function Details($id){
        if (null != Session::getSession('User')){
            $response = $this->model->GetShoppings(
                null,
                $id,
                null,
                null,
                null,
                null
            );
            if (is_array($response)) {
                if (0 < count($response['results'])) {
                    $this->view->Render($this, 'details', $response['results'], $this->Money, null);
                }else{
                    header('Location:' . URL . 'Shopping/Shoppings');
                }
            }else{
                header('Location:' . URL . 'Shopping/Shoppings');
            }
        }else{
            header('Location:' . URL);
        }
    }
    public function Cancel()
    {
        Session::setSession('model1', '');
        Session::setSession('model2', '');
        header('Location: AddShoppings/' . Session::getSession('page'));
    }
}
