<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Query
 *
 * @author alu2015059
 */
class Query {

    var $session, $id, $open, $cache;

    public function __construct($s, $q) {
        $this->session = $s;
        $this->id = $this->exec(chr(0), $q);
    }

    public function bind($name, $value, $type = "") {
        $this->exec(chr(3), $this->id . chr(0) . $name . chr(0) . $value . chr(0) . $type);
    }

    public function context($value, $type = "") {
        $this->exec(chr(14), $this->id . chr(0) . $value . chr(0) . $type);
    }

    public function execute() {
        return $this->exec(chr(5), $this->id);
    }

    public function more() {
        if ($this->cache == NULL) {
            $this->pos = 0;
            $this->session->send(chr(4) . $this->id . chr(0));
            while (!$this->session->ok()) {
                $this->cache[] = $this->session->readString();
            }
            if (!$this->session->ok()) {
                throw new Exception($this->session->readString());
            }
        }
        if ($this->pos < count($this->cache))
            return true;
        $this->cache = NULL;
        return false;
    }

    public function next() {
        if ($this->more()) {
            return $this->cache[$this->pos++];
        }
    }

    public function info() {
        return $this->exec(chr(6), $this->id);
    }

    public function options() {
        return $this->exec(chr(7), $this->id);
    }

    public function close() {
        $this->exec(chr(2), $this->id);
    }

    public function exec($cmd, $arg) {
        $this->session->send($cmd . $arg);
        $s = $this->session->receive();
        if ($this->session->ok() != True) {
            throw new Exception($this->session->readString());
        }
        return $s;
    }

}