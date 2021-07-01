<?php
require( 'fpdf182/fpdf.php' );
class Ticket extends FPDF
{
    private $maxCaracter = 40;
    private $stop;
    private $lineas;

    public function __construct() {
        parent::__construct();
        $this->AddPage();
    }
    public function LineasGuion(){
        $lineas = '';
        for ( $i = 0; $i < $this->maxCaracter; $i++ ){
            $lineas .= '-';
        }
        $this->SetFont( 'Arial', 'B', 16 );
        $this->Cell( $this->maxCaracter, 10, $lineas, 0, 0, 'L' );
        $this->Ln( 5 );
    }
    public function LineAsteriscos(){
        $asterisco = '';
        for ( $i = 0; $i < $this->maxCaracter; $i++ ){
            $asterisco .= '*';
        }
        $this->SetFont( 'Arial', 'B', 16 );
        $this->Cell( $this->maxCaracter, 10, $asterisco, 0, 0, 'C' );
        $this->Ln( 5 );
    }
    public function TextoIzquierda($texto){
        $this->SetFont( 'Arial', 'B', 9 );
        $Length = strlen ( $texto );
        if ($Length > $this->maxCaracter){
            $caracterActual = 0;
            for ($i = $Length; $i > $this->maxCaracter; $i -= $this->maxCaracter){
                $rest = substr($texto, $caracterActual,$this->maxCaracter); 
                $caracterActual += $this->maxCaracter;
                $this->Cell( $this->maxCaracter, 10, $rest, 0, 0, 'L' );
                $this->Ln( 5 );
            }
            $rest = substr($texto, $caracterActual,$Length-$caracterActual);
            $this->Cell( $this->maxCaracter, 10, $rest, 0, 0, 'L' );
            $this->Ln( 5 );
        }else{
            $this->Cell( $this->maxCaracter, 10, $texto, 0, 0, 'L' );
            $this->Ln( 5 );
        }
    }
    public function TextoDerecho($texto){
        $this->SetFont( 'Arial', 'B', 9 );
        $Length = strlen ( $texto );
        if ($Length > $this->maxCaracter){
            $caracterActual = 0;
            for ($i = $Length; $i > $this->maxCaracter; $i -= $this->maxCaracter){
                $rest = substr($texto, $caracterActual,$this->maxCaracter); 
                $caracterActual += $this->maxCaracter;
                $this->Cell( $this->maxCaracter, 10, $rest, 0, 0, 'R' );
                $this->Ln( 5 );
            }
            $espacios = '';
            $rest = substr($texto, $caracterActual,$Length-$caracterActual);
            for ($i = 0; $i < $this->maxCaracter-strlen ( $rest ); $i++){
                $espacios .= ' ';
            }
            $this->Cell( $this->maxCaracter, 10, $espacios.$rest, 0, 0, 'R' );
            $this->Ln( 5 );
        }else{
            $espacios = '';
            for ($i = 0; $i < $this->maxCaracter-strlen ( $texto ); $i++){
              $espacios .= ' ';
            }
  
            $this->Cell( $this->maxCaracter, 10, $espacios.$texto, 0, 0, 'R' );
            $this->Ln( 5 );
        }
    }
    public function TextoCentro($texto){
        $this->SetFont( 'Arial', 'B', 9 );
        $Length = strlen ( $texto );
        if ($Length > $this->maxCaracter){
            $caracterActual = 0;
            for ($i = $Length; $i > $this->maxCaracter; $i -= $this->maxCaracter){
                $rest = substr($texto, $caracterActual,$this->maxCaracter); 
                $caracterActual += $this->maxCaracter;
                $this->Cell( $this->maxCaracter, 10, $rest, 0, 0, 'C' );
                $this->Ln( 5 );
            }
            $espacios = '';
            $rest = substr($texto, $caracterActual,$Length-$caracterActual);
            $centrar = strlen ( $rest ) /2;
            for ($i = 0; $i < $centrar; $i++){
                $espacios .= ' ';
            }
            $this->Cell( $this->maxCaracter, 10, $espacios.$rest, 0, 0, 'C' );
            $this->Ln( 5 );
        }else{
            $espacios = '';
            $centrar = ( $this->maxCaracter - $Length ) /2;
            for ($i = 0; $i < $centrar; $i++){
                $espacios .= ' ';
            }
            $this->Cell( $this->maxCaracter, 10, $espacios.$texto, 0, 0, 'C' );
            $this->Ln( 5 );
        }
    }
    public function TextoExtremo($izquierdo, $derecho){
        $this->SetFont( 'Arial', 'B', 9 );
        $der = ""; $izq = ""; $completo1 = ""; $espacio1 = "";
        $espacio2 = ""; $completo2 = "";
        if (strlen ( $izquierdo ) > 17){
            $izq = substr($izquierdo, 0,17);
        }else{
            $izq = $izquierdo;
        }
        if (strlen ( $derecho )  > 17){
            $der = substr($derecho, 0,17);
        }else{
            $der = $derecho;
        }
        $numEspacios = (17 -strlen ( $izq ));
      
        for ($i = 0; $i <=$numEspacios; $i++){
          $espacio1 .= " ";
        }
        $completo1 .= $izq . $espacio1;
      
        //$numEspacios = (17 -strlen ( $der ));
        
        for ($i = 0; $i <=10; $i++){
          $espacio2 .= " ";
        }
        $completo2 .= $espacio2 . $der;
        $this->Cell( $this->maxCaracter, 10, $completo1, 0, 0, 'L' );
        $this->Cell( $this->maxCaracter, 10, $completo2, 0, 0, 'L' );
        $this->Ln( 5 );
    }
    public function AgregarArticulo($articulo, $cant, $precio){
        $elemento1 = "";
        $elemento2 = "";
        $espacios = "";
        $numEspacios = 15;
        $this->SetFont('Arial', 'B', 9);
        if (strlen($articulo) > 20){
            //colocar la cantida a la derecha
            $espacios = "";
            for ($i = 0; $i < ($numEspacios - strlen($cant)); $i++){
                $espacios .= " ";
            } 
            $elemento1 = $cant . $espacios;
            //colocar el precio a la derecha
            $espacios = "";
            for ($i = 0; $i < ($numEspacios - strlen($precio)); $i++){
                $espacios .= " ";
            }
            $elemento1 .= $precio . $espacios;
            $elemento2 .= substr($articulo, 0, 18);
            $this->Cell($this->maxCaracter, 10, $elemento2, 0, 0, 'L');
            $this->Cell($this->maxCaracter, 10, $elemento1, 0, 0, 'L');
            $this->Ln(5);
        } else{
            for ($i = 0; $i < (40 - strlen($articulo)); $i++){
                $espacios .= " ";  // Agrega espacios para poner el valor de articulo
            }
            $elemento1 = $articulo . $espacios;
            //colocar la cantidad a la drecha
            $espacios = "";
            for ($i = 0; $i < ($numEspacios - strlen($cant)); $i++) {
                $espacios .= " ";   // Agrega espacios para poner el valor de cantidad
            }
            $elemento2 .= $cant . $espacios;
            //colocamos el precio a la derecha
            $espacios = "";
            for ($i = 0; $i < ($numEspacios - strlen($cant)); $i++){
                $espacios .= " "; // Agrega espacios para poner el valor de precio
            }
            $elemento2 .= $precio . $espacios;
            $this->Cell($this->maxCaracter, 10, $elemento1, 0, 0, 'L');
            $this->Cell($this->maxCaracter, 10, $elemento2, 0, 0, 'L');
            $this->Ln(5);
        }
    }
    public function AgregarTotales($texto, $total, $money){
        $stop = 0;
        $resumen = "";
        $completo = "";
        $espacio = "";
        $valor = "";
        $numEspacios = 15;
        $this->SetFont('Arial', 'B', 9);
        if (strlen($texto) > 20) {
            $resumen .= substr($texto, 0, 18);
        }else{
            $resumen = $texto;
        }
        $valor = $money . DecimalFormat::number_format($total); 
        for ($i = 0; $i < $numEspacios; $i++){
            $espacio .= " ";
        }       
        $completo = $espacio . $valor;
        $this->Cell($this->maxCaracter, 10, $resumen, 0, 0, 'L');
        $this->Cell($this->maxCaracter, 10, $completo, 0, 0, 'L');
        $this->Ln(5);
    }
    public function PDF($dir,$code,$name){
        $path = "Tickets/$dir/$name";
        if (!file_exists($path)){
            mkdir($path, 0777, true);
            $this->Output( 'F', "$path/$code.pdf" );
        }else{
            $this->Output( 'F', "$path/$code.pdf" );
        }
        
    }
    public function PDFdownload($code,$name){
        $this->Output( 'D', "$name-$code.pdf" );
    }
}


?>