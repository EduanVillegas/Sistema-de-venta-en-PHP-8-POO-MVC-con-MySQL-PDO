<?php
class SettingController extends Controllers{
    public function __construct() {
        parent::__construct();
    }
    public function Setting(){
        if ( null != Session::getSession( 'User' ) ){
            $model1 = Session::getSession( 'TypeMoney' );
            if ( null != $model1 ){
                $array1 = unserialize( $model1 );
                if ( is_array( $array1 ) ){
                    $model1 = $this->TSetting( $array1 );
                    Session::setSession( 'TypeMoney', serialize( array(
                        'TypeMoney'=>'',
                    ) ) );
                    $this->view->Render( $this, 'setting',  $model1, Setting::$setting, null );

                }else{
                    $this->view->Render( $this, 'setting', $this->TSetting( array() ), Setting::$setting, null );
                }
            }else{
                $this->view->Render( $this, 'setting', $this->TSetting( array() ), Setting::$setting, null );
            }
            
        }else{
            header( 'Location:'.URL );
        }
    } 
    public function TypeMoney() {
        if ( !empty( $_POST['RadioOptions'] ) ) {
            $value = $this->model->TypeMoney( 
                $_POST['RadioOptions'] ,
                $this->TSetting( array() )
             );
             if (!$value) {
                Session::setSession( 'TypeMoney', serialize( array(
                    'TypeMoney'=>'Ocurrió un problema al registrar el tipo de moneda',
                ) ) );
             }
        }else{
            Session::setSession( 'TypeMoney', serialize( array(
                'TypeMoney'=>'Seleccione un tipo de moneda',
            ) ) );
        }
        header( 'Location:'.URL."Setting/Setting" );
    }
    public function SetInterests(){
        if ( !empty( $_POST['Interests'] ) ){
            $value = $this->model->SetInterests(
                $_POST['Interests'],
                $this->TSetting( array() )
            );
            if ( !$value ) {
                Session::setSession( 'TypeMoney', serialize( array(
                    'TypeMoney'=>'Ocurrió un problema al registrar los intereses',
                ) ) );
            }
        }else{
            Session::setSession( 'TypeMoney', serialize( array(
                'TypeMoney'=>'Ingresar los intereses a registrar',
            ) ) );
        }
        header( 'Location:'.URL.'Setting/Setting' );
    }  
}
?>