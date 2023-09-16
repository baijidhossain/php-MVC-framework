<?php

class HomeModel {

    /**
     * HomeModel constructor.
     */
    private $db;

    public function __construct()
    {

        $this->db = new Database;
    }

}