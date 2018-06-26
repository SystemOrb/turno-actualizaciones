<?php
/*
 * Clase que modifica el calendario según los dias laborables
 * Carlos Estarita
 * Modificación de Calendario en turnonet
 */
include_once 'es/app/model/config.php';
include_once 'es/app/model/connection.php';
class Calendario {
     public function __construct() {
        $this->BBDD = new dbDriver();
        $this->driver = $this->BBDD->setPDO();
        $this->BBDD->setPDOConfig($this->driver);
    }
    /*
     * Retornamos un objeto para recorrerlo y verificar   
     * todos los dias que tenga cargado dicha empresa
     */
     public function getDiaLab($empid) {
         try {
             $dialab = $this->BBDD->selectDriver('lab_empid = ?',PREFIX.'dlab', $this->driver);
             $this->BBDD->runDriver(array($this->BBDD->scapeCharts($empid)), $dialab);
             if ($this->BBDD->verifyDriver($dialab)) {
                 return $this->BBDD->fetchDriver($dialab);
             } else {
                 return null;
             }
         } catch (PDOException $ex) {
             throw new Exception('Fallo al cargar la base de datos'. $ex->getMessage(). PHP_EOL. ' '. $ex->getFile() );
         }
     }
     /*
      * Función que por cada vuelta del bucle verifica si trabaja o no ese dia
      */
     public function verificaDia($lab_dian) {
         try {
             $dialab = $this->BBDD->selectDriver('lab_empid = ?',PREFIX.'dlab', $this->driver);
             $this->BBDD->runDriver(array($this->BBDD->scapeCharts($empid)), $dialab);
             foreach( $this->BBDD->fetchDriver($dialab) as $booleanDia ) {
                 if ($booleanDia->lab_dian === $lab_dian) {
                     return true;
                 } else {
                     return false;
                 }
             }
         } catch (PDOException $ex) {
             throw new Exception('Fallo al cargar la base de datos'. $ex->getMessage(). PHP_EOL. ' '. $ex->getFile() );
         }
     }
     private $BBDD;
     private $driver;
}