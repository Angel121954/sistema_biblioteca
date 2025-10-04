<?php

class usuarios {
    private $nombre;
    private $documento;
    private $id;

    public function __construct() {
    }

    public function set_nombre($nombre) {
        $this -> nombre = $nombre;
    }

    public function get_nombre($nombre) {
        return $this -> $nombre;
    }

    public function set_documento($documento) {
        $this -> documento = $documento;
    }

    public function get_documento($documento) {
        return $this -> $documento;
    }

    public function set_id($id) {
        $this -> id = $id;
    }

    public function get_id($id) {
        return $this -> $id;
    }
}

?>