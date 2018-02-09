<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseXClient
 *
 * @author alu2015059
 */
class BaseXClient {
    
    var $socket, $info, $buffer, $bpos, $bsize;
    
    function construct($h, $p, $u, $pw){
        $this-> socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if(!socket_connect($this->socket, $h, $p)){
            throw new Exception("No hay conexion");
        }
        $ts = $this->readString();
        if(FALSE !== strpos($ts, ':')){
            $challenge = explode(':', $ts, 2);
            $md5 = hash("md5", hash("md5", $u . ':' . $challenge[0] . ':' . $pw) . $challenge[1]);
        } else {    
        $md5 = hash("md5", hash("md5", $pw) . $ts);
        }
        socket_write($this->socket, $u . chr(0).$md5.chr(0));
        if(socket_read($this->socket, 1) != chr(0)){
            throw new Exception("Acceso denegado");
        }
    }
    
    public function query($q){
        return new Query($this, $q);
    }

    public function close(){
        socket_write($this->socket, "exit" . chr(0));
        socket_close($this->socket);
    }

        private function init(){
        $this->bpos = 0;
        $this->bsize = 0;
    }
    
    public function ejecutar($comand){
        socket_write($this->socket, $comand.chr(0));
        $resultado = $this->recieve();
        $this->info = $this->readString();
        if($this->ok() != TRUE){
            throw new Exception($this->info);
        }
    }
    
    public function readString(){
        $comand = "";
        while (($d = $this->read()) != chr(0)){
            $comand .= $d;
        }
        return $comand;
    }
    
    private function read(){
        if($this->bpos == $this->bsize){
            $this->bsize = socket_recv($this->socket, $this->buffer, 4096, 0);
            $this->bpos = 0;
        }
        return $this->buffer[$this->bpos++];
    }
    
    public function ok(){
        return $this->read() == chr(0);
    }
    
    public function recieve(){
        $this->init();
        return $this->readString();
    }
}
