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
        $sql = "SELECT equi.id, equi.tipo, equi.texto, equi.activo, img.imagen 
        FROM equipo equi 
        LEFT OUTER JOIN equipo_imagen equimg ON equi.id = equimg.equipo_id 
        LEFT OUTER JOIN imagen img ON equimg.imagen_id = img.id 
        ORDER BY equi.id;";
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
        $sql = "INSERT INTO equipo (id, tipo, texto, activo) VALUES ($id, '$_tipo->tipo', '$_texto->texto', false);";
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
        $sql = "UPDATE equipo SET activo = $_accion WHERE id = $_id;";
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
    {   $con = new Conexion();
        $sql = "UPDATE equipo SET texto = '$_texto->texto' WHERE id = '$_id->id';";
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
        $sql = "DELETE FROM equipo WHERE id = $_id;";
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