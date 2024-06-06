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
        $sql = "SELECT * FROM info_contacto;";
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

    public function postNuevo($body)
    {
        $con = new Conexion();
        $id = count($this->getAll()) + 1;
        $sql = "INSERT INTO info_contacto (id, nombre, icono, texto, texto_adicional, activo) 
                VALUES ($id, '$body->nombre', '$body->icono', '$body->texto', '$body->texto_adicional', false);";
        $rs = false;
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = false;
        }
        $con->closeConnection();
        if ($rs) {
            return $rs;
        }
        return null;
    }

    public function patchEncenderApagar($id, $accion)
    {
        $con = new Conexion();
        $sql = "UPDATE info_contacto SET activo = $accion WHERE id = $id;";
        $rs = false;
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = false;
        }
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return null;
    }

    public function putTextoById($body, $id)
    {
        $con = new Conexion();
        $sql = "UPDATE info_contacto SET nombre = '$body->nombre', icono = '$body->icono', 
        texto = '$body->texto', texto_adicional = '$body->texto_adicional' WHERE id = $id;";
        $rs = false;
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = false;
        }
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return null;
    }

    public function deleteById($id)
    {
        $con = new Conexion();
        $sql = "DELETE FROM info_contacto WHERE id = $id;";
        $rs = false;
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = false;
        }
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return null;
    }
}
?>
