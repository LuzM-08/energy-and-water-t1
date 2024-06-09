<?php

class Controlador
{
    private $lista;

    public function __construct()
    {
        $this->lista = [];
    }

    public function getAll()
    {
        $con = new Conexion();
        $sql = "SELECT his.id, his.tipo, his.texto, his.activo, img.imagen 
                FROM historia his 
                LEFT OUTER JOIN historia_imagen hisimg ON his.id = hisimg.historia_id 
                LEFT OUTER JOIN imagen img ON hisimg.imagen_id = img.id 
                ORDER BY his.id;";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
            while ($tupla = mysqli_fetch_assoc($rs)) {
                array_push($this->lista, $tupla);
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $this->lista;
    }

    public function postNuevo($_tipo, $_texto)
    {
        $con = new Conexion();
        $id = count($this->getAll()) + 1;
        $sql = "INSERT INTO historia (id, tipo, texto, activo) VALUES ($id, '$_tipo->tipo', '$_texto->texto', false);";
        $rs = false;
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = false;
        }
        // cerramos la conexion
        $con->closeConnection();
        // comprobamos la respuesta
        if ($rs) {
            return $rs;
        }
        return null;
    }

    public function patchEncenderApagar($_id, $_accion)
    {
        $con = new Conexion();
        $sql = "UPDATE historia SET activo = $_accion WHERE id = $_id;";
        $rs = false;
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = false;
        }
        // cerramos la conexion
        $con->closeConnection();
        // comprobamos la respuesta
        if ($rs) {
            return true;
        }
        return null;
    }

    public function putTextoById($_texto, $_id)
    {   
        $con = new Conexion();
        $sql = "UPDATE historia SET texto = '$_texto->texto' WHERE id = '$_id->id';";
        // echo $sql;
        $rs = false;
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = false;
        }
        // cerramos la conexion
        $con->closeConnection();
        // comprobamos la respuesta
        if ($rs) {
            return true;
        }
        return null;
    }

    public function deleteById($_id)
    {
        $con = new Conexion();
        $sql = "DELETE FROM historia WHERE id = $_id;";
        $rs = false;
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = false;
        }
        // cerramos la conexion
        $con->closeConnection();
        // comprobamos la respuesta
        if ($rs) {
            return true;
        }
        return null;
    }
}
?>