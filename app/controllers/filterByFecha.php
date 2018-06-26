<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type");
header("Access-Control-Allow-Methods", "POST, GET, PUT, DELETE, OPTIONS");
require_once '../es/app/model/config.php';
require_once '../es/app/model/connection.php';
class filtroFecha {
    public function __construct() {
        $this->BBDD = new dbDriver();
        $this->driver = $this->BBDD->setPDO();
        $this->BBDD->setPDOConfig($this->driver);
    }
    public function UsuarioPorNombre($val) {
        try {
            $usr = $this->BBDD->selectDriver('us_nom LIKE ?',PREFIX.'users',$this->driver);
            $this->BBDD->runDriver(array(
                $this->BBDD->scapeCharts("%$val%")
            ), $usr);
            if ($this->BBDD->verifyDriver($usr)) {
                return $this->BBDD->fetchDriver($usr);
            } else {
                return null;
            }
        } catch (Exception $ex) {
            throw new Exception('No puede conectar con la base de datos'. $ex->getCode(). PHP_EOL. ' '. $ex->getMessage());
        }
    }
    public function UsuarioPorDni($val) {
        try {
            $usr = $this->BBDD->selectDriver('us_dni LIKE ?',PREFIX.'users',$this->driver);
            $this->BBDD->runDriver(array(
                $this->BBDD->scapeCharts("%$val%")
            ), $usr);
            if ($this->BBDD->verifyDriver($usr)) {
                return $this->BBDD->fetchDriver($usr);
            } else {
                return null;
            } 
        } catch (Exception $ex) {
            throw new Exception('No puede conectar con la base de datos'. $ex->getCode(). PHP_EOL. ' '. $ex->getMessage());
        }        
    }
    public function UsuarioPorEmail($val) {
        try {
            $usr = $this->BBDD->selectDriver('us_mail LIKE ?',PREFIX.'users',$this->driver);
            $this->BBDD->runDriver(array(
                $this->BBDD->scapeCharts("%$val%")
            ), $usr);
            if ($this->BBDD->verifyDriver($usr)) {
                return $this->BBDD->fetchDriver($usr);
            } else {
                return null;
            }            
        } catch (Exception $ex) {
            throw new Exception('No puede conectar con la base de datos'. $ex->getCode(). PHP_EOL. ' '. $ex->getMessage());
        }        
    }
    public function getTurnosUsuario($cli_id,$empid) {
         try {
            $sql = 'us_id = ? 
                    && emp_id = ?
                    && tu_estado = ? 
                    GROUP BY us_id 
                    ORDER BY tu_fec 
                    ASC LIMIT 1;
                    ';
            $cliTurno = $this->BBDD->selectDriver($sql,PREFIX.'turnos',$this->driver);
            $this->BBDD->runDriver(array(
                $this->BBDD->scapeCharts($cli_id),
                $this->BBDD->scapeCharts($empid),
                $this->BBDD->scapeCharts('BAJA')
            ), $cliTurno);
            if ($this->BBDD->verifyDriver($cliTurno)) {
                return $this->BBDD->fetchDriver($cliTurno);
            } else {
                return false;
            }
        } catch (Exception $ex) {
            throw new Exception('No puede conectar con la base de datos'. $ex->getCode(). PHP_EOL. ' '. $ex->getMessage());
        }    
    }
    public function closeConnection() {
        $this->BBDD = NULL;
    }
    protected $BBDD;
    protected $driver;
}

