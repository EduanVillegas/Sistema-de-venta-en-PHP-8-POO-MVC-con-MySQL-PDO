<?php
class Controllers extends AnonymousClasses
{
    public function __construct() {
        date_default_timezone_set('America/Tegucigalpa');
        Session::star();
        $this->view = new Views();
        $this->role = new Roles();
        $this->paginador = new Paginador();
        $this->setting = new Setting();
        $this->loadClassmodels();
    }
    public function loadClassmodels()
    {
        $array = explode("Controller",get_class($this));
        $model = $array[0].'_model';
        $path = 'Models/'.$model.'.php';
        if(file_exists($path)){
            require $path;
            $this->model = new $model();
        }
    }
}

?>