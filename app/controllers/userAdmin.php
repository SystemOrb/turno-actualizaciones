<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type");
header("Access-Control-Allow-Methods", "POST, GET, PUT, DELETE, OPTIONS");
include_once '../es/app/model/config.php';
include_once '../es/app/model/connection.php';
class userAdmin {
    public function __construct() {
        $this->BBDD = new dbDriver();
        $this->driver = $this->BBDD->setPDO();
        $this->BBDD->setPDOConfig($this->driver);
    }
    public function VerifyAdmin($tucan_usid) {
        try {
            $admin = $this->BBDD->selectDriver('us_id = ?',PREFIX.'users_admins', $this->driver);
            $this->BBDD->runDriver(array($this->BBDD->scapeCharts($tucan_usid)), $admin);
            /*
             * Verificamos si es un administrador, sino devuelve nada
             * significa que es un administrador principal es decir
             * el dueño
             */
            if ($this->BBDD->verifyDriver($admin)) {
                // Entonces retornamos un arreglo Object con el usuario
                return $this->BBDD->fetchDriver($admin);
            } else {
                // Entonces retorna nulo
                return null;
            } 
        } catch (Exception $ex) {
            throw new Exception('Error grave en el sistema' . $ex->getMessage(). PHP_EOL. ' ' . $ex->getLine());
        }
    }
    protected $BBDD;
    protected $driver;
}
