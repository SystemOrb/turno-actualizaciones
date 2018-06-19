<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type");
header("Access-Control-Allow-Methods", "POST, GET, PUT, DELETE, OPTIONS");
        require_once '../model/config.php';
        require_once '../model/connection.php';
	include_once ('../../../js/func.php');
	include_once ('../../../js/cambiocaracteres.php'); 
	include_once ('../../../js/class.php'); 
	include_once('../../../js/globalinc.php');
	include_once ('../../../js/loginpconf.php');
/*
 * Buscador inteligente
 * Permitirá buscar por fecha, DNI o nombre
 * 
 * Carlos Estarita
 */

if($_GET) {
    $search = new explorer();
    echo $search->intellisence($_GET['pac_nom']);
}
class explorer { 
     public function __construct() {
        $this->BBDD = new dbDriver();
        $this->driver = $this->BBDD->setPDO();
        $this->BBDD->setPDOConfig($this->driver);
    }
     public function intellisence($query) {
         /*
          * Comprobamos el tipo de query para verificar si es busqueda por Email, DNI o nombre
          */
         if (ctype_digit($query) && (strlen($query >= 6))) {
              echo $this->turnoEmpleado('dni');
         } else { 
         if (filter_var($query, FILTER_VALIDATE_EMAIL)) {
              echo $this->turnoEmpleado('email');
         } else {
              echo $this->turnoEmpleado('name');
             
         }
     }
   }
     private function searchByTurno($collection) {
         if ( $this->searchEmploy($_GET['empid']) ) {
             try {
                 $usrEmail = $this->BBDD->selectDriver("{$collection} = ?",PREFIX.'users', $this->driver);
                 $this->BBDD->runDriver(array(
                     $this->BBDD->scapeCharts($_GET['pac_nom'])
                 ), $usrEmail);
                 if ($this->BBDD->verifyDriver($usrEmail)) {
                     // Devolvemos sus datos
                       return $this->BBDD->fetchDriver($usrEmail);
                 } else {
                     return null;
                 }
             } catch (PDOException $ex) {
              die('No pudo conectar con la base de datos'. $ex->getCode() . ' '. $ex->getMessage());
             }
         }
     }
     /*
      * Función para hacer busqueda por EMAIL
      */
     public function turnoEmpleado($type) {
         switch($type) {
             case 'name':
                 $usr_verification = $this->searchByTurno('us_nom');
                 break;
             case 'email':
                 $usr_verification = $this->searchByTurno('us_mail');
                 break;
             case 'dni':
                 $usr_verification = $this->searchByTurno('us_dni');
                 break;
             default: 
                 $usr_verification = $this->searchByTurno('us_nom');
         }         
         if ($usr_verification != null) {
                foreach($usr_verification as  $data) {
                $rootdir = "http://www.turnonet.com/";
                $utf_nom = utf8_encode($data->us_nom);
                $sql = 'emp_id = ? && tu_estado = ? && us_id = ?';
                $turno = $this->BBDD->selectDriver($sql,PREFIX.'turnos', $this->driver);
                $this->BBDD->runDriver(array(
                    $this->BBDD->scapeCharts($_GET['empid']),
                    $this->BBDD->scapeCharts('ALTA'),
                    $this->BBDD->scapeCharts($data->us_id)
                ), $turno);
                if ($this->BBDD->verifyDriver($turno)) {                   
                    foreach($this->BBDD->fetchDriver($turno) as $items) {
                        echo "<div class='pacadm'>";
                        if ($this->contadorTurnos($items->us_id) === 0) {
                            echo "
                                  <div class='chdel'>
                                  <a href='javascript:void(0)'
                                  title='Eliminar paciente'
                                  class='delpac'
                                  id='tuid_{$items->us_id}'>
                                  <img src='{$rootdir}imagenes/menu/ic_del.png' 
                                      width='25'
                                      height='25'
                                      class='img_b'>
                                   </a>
                                  </div>                               
                                 "; 
                        }
                        echo "
                            <div class='chara'>
                            <a href='javascript:void(0)' 
                            class='lpact2'
                            id='{$items->us_id}'>
                             {$this->contadorTurnos($items->us_id)} Turno
                            </a>                           
                             ";
                             if ($_SERVER['REMOTE_ADDR'] == '181.171.232.97' || $_GET['empid'] == '929') {
                                 echo "
                                     <a href='{$rootdir}administrar-agendas/v2/imppres/{$this->getEmpsDetails($_GET['empid'])}/{$items->us_id}'
                                         title='Imprimir Datos del paciente'
                                         target='_blank'
                                         id='impdid_{$items->us_id}'>
                                         <img src='{$rootdir}imagenes/menu/ic_print.php' 
                                             width='25'
                                             height='25'
                                             class='img_b'>
                                     </a>

                                      ";
                             }
                             echo "
                                 <a href='javascript:void(0)'
                                    title='Datos del paciente'
                                    class='lodin2'
                                    id='tuid_{$items->us_id}'>
                                        <img src='{$rootdir}imagenes/menu/ic_usp.png'
                                            width='25'
                                            height='25'
                                            class='img_b'>
                                  </a>
                                  <a href='javascript:void(0)'
                                     title='Seleccionar para Carga de Turno'
                                     class='tooltip'
                                     onclick='showu('{$items->us_id}','{$utf_nom}', '{$data->us_mail}')'>
                                         <img src='{$rootdir}imagenes/menu/ic_ctur.png'
                                             width='25'
                                             height='25'
                                             class='img_b'>
                                  </a>
                                  </div>
                                  ";
                                         echo utf8_encode($utf_nom);
                                         echo "<br>";
                                         echo "{$data->us_mail}";
                                         echo "<br>" . "<br>";
                                 echo "</div>";

                    }           
                } else {
                  $err = array();
                  $err['status'] = false;
                  $err['message'] = 'No existe este usuario';
                  return json_encode($err);
                }
            }
         } else {

         }
     }
     /*
      * Función que retorna un Boolean si existe o no existe la empresa
      */
     private function searchEmploy($empid) {
         try {
            $empresa = $this->BBDD->selectDriver('em_id = ?',PREFIX.'emps', $this->driver);
            $this->BBDD->runDriver(array(
                $this->BBDD->scapeCharts($empid)
            ), $empresa);
            if ($this->BBDD->verifyDriver($empresa)) {
                 return true;
            } else {
                return false;
            }
         } catch (PDOException $ex) {
             die('No pudo conectar con la base de datos'. $ex->getCode() . ' '. $ex->getMessage());
         }
     }
     private function getEmpsDetails($empid) {
         try {
             $emps = $this->BBDD->selectDriver('em_id = ?',PREFIX.'emps', $this->driver);
             $this->BBDD->runDriver(array(
                 $this->BBDD->scapeCharts($empid)
             ), $emps);
             foreach ($this->BBDD->fetchDriver($emps) as $em_us) {
                 return $em_us->em_uscid;
             }
         } catch (PDOException $ex) {
             throw new Exception('No se reconoce los datos de la empresa');
         }
     }
     /* CONTADOR DE TURNOS */
     private function contadorTurnos($usr_id) {
         try {
             $FLAG = $this->BBDD->countDriver('us_id = ?',PREFIX.'turnos', $this->driver);
             $this->BBDD->runDriver(array(
                 $this->BBDD->scapeCharts($usr_id)
             ), $FLAG);
             foreach (($this->BBDD->fetchDriver($FLAG)) as $count) {
                 return $count->index;
             }
         } catch (Exception $ex) {
             throw new Exception('Fallo al conectar con la base de datos' . $ex->getCode(). PHP_EOL . $ex->getMessage());
         }
     }
     public function jquery() {

     }
     
    protected $BBDD;
    protected $driver;
}
