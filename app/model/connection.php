<?php
/*
 * Código que se encarga de construir las conexiones de forma dinamica
 * Para construir una API a futuro
 * Carlos Estarita
 */
require_once 'config.php';
// Creamos una clase que funcionará como un driver
class dbDriver
/*
 * Clase que genera las conexiones dinamicamente
 */
{
    public function __construct() { 
    }
    public function setPDO()
    {
        return new PDO(PDO_HOSTNAME, PDO_USER, PDO_PASS);
    }
    /*************************************************
     * Seteamos las configuraciones
     *************************************************/
    public function setPDOConfig($PDO_CONSTRUCTOR)
    {
        $obj = $PDO_CONSTRUCTOR;
        $obj->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $obj->exec(PDO_CHAR);
    }
    /*************************************************
     * GETTER  / SETTER de la BBDD
     *************************************************/    
    public function setBBDD($object){
        $this->BBDD = $object;
    }
    public function getBBDD(){
        return $this->BBDD;
    }
     /*************************************************
     * SQL Select con Condiciones y sin condicion
      *  de forma dinámica
     *************************************************/
    public function selectDriver($condition,$bbdd, $PDO_port)
    {
        if($condition!=null){
           return $PDO_port->prepare("SELECT * FROM {$bbdd} WHERE {$condition}");
        }else{
           return $PDO_port->prepare("SELECT * FROM {$bbdd}");
            
            }       
    }
     /*************************************************
     * Para evitar las inyecciones
     *************************************************/
    public function scapeCharts($value)
    {
        return htmlentities(addslashes($value));
    }
     /*************************************************
     * SQL COUNT
     *************************************************/
    public function countDriver($condition,$bbdd,$PDO_port)
    {
        if($condition!=null)
        {
           return $PDO_port->prepare("SELECT COUNT(*) as 'index'  FROM {$bbdd} WHERE {$condition}");
        }else{
            return $PDO_port->prepare("SELECT COUNT(*) as 'index'  FROM {$bbdd}");
        }
    }
     /*************************************************
     * SQL SUM ROW POR GRUPO
     *************************************************/
    public function countDriverByGroup($condition,$bbdd,$PDO_port,$field,$group)
    {
        if($condition!=null)
        {
            return $PDO_port->prepare("SELECT SUM({$field} as index FROM {$bbdd} WHERE {$condition} GROUP BY {$group} ASC");
        }else{
            return $PDO_port->prepare("SELECT SUM{$field} as index FROM {$bbdd} GROUP BY {$group} ASC ");
        }
    }
     /*************************************************
     * SQL SUM ROW
     *************************************************/
    public function sumDriver($condition,$bbdd,$PDO_port,$field)
    {
        if($condition!=null)
        {
            return $PDO_port->prepare("SELECT SUM({$field}) AS total FROM {$bbdd} WHERE {$condition}");
        }else{
            return $PDO_port->prepare("SELECT SUM({$field}) AS total FROM {$bbdd}");
        }
    }
    /*************************************************
     * INSERTAR UN NUEVO REGISTRO EN LA BASE DE DATOS
     *************************************************/
    public function insertDriver($condition,$bbdd,$PDO_port,$fields)
    {
        return $PDO_port->prepare("INSERT INTO {$bbdd}({$fields}) VALUES({$condition})");
    }
     /*************************************************
     * ACTUALIZAR UN REGISTRO EN LA BASE DE DATOS
     *************************************************/
    public function updateDriver($condition,$bbdd,$PDO_port,$fields){
        return $PDO_port->prepare("UPDATE {$bbdd} SET {$fields} WHERE {$condition}");
    }
     /*************************************************
     * BORRAR UN REGISTRO
     *************************************************/
    public function deleteDriver($condition,$bbdd,$PDO_port)
    {
        if($condition!='')
        {
            return $PDO_port->prepare("DELETE FROM {$bbdd} WHERE {$condition}");
        }else{
            return $PDO_port->prepare("DELETE FROM {$bbdd}");
        }
    }
     /*************************************************
     * EJECUTAR LA SENTENCIA DE LA BBDD (EXECUTE)
     *************************************************/
   public function runDriver($sentence,$PDO_OBJECT)
   {
       if($sentence!=null)
       {
           $PDO_OBJECT->execute($sentence);
       }else{
           $PDO_OBJECT->execute();
       }
   }
    /*************************************************
     * DEVOLVEMOS EL OBJETO RESPUESTA 
     *************************************************/
   public function fetchDriver($PDO_OBJECTS)
   {
       return $PDO_OBJECTS->fetchAll(PDO::FETCH_OBJ);
   }
     /*************************************************
     * VERIFICAMOS SI DEVUELVE REGISTROS
     *************************************************/
   public function verifyDriver($PDO_OBJECT)
   {
       if($PDO_OBJECT->rowCount()!=0)
       {
           return true;
       }else{
           return false;
       }
   }
     /*************************************************
     * GETTERS / SETTERS
     *************************************************/
    public function setobjectPDO($PDO_OBJECT)
    {
        return $PDO_OBJECT;

    }
    public function getObjectPDO()
    {
        return $this->PDO;
    }
    public function setQuery($arrayResponse)
    {
        $this->query = $arrayResponse;
    }
    public function getQuery()
    {
        return $this->query;
    }
    protected $BBDD;
    protected $query;
    protected $PDO;
}

