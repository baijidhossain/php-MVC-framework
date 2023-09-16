<?php

class Neighborhood extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Neighborhood');
  }

  public function index()
  {
  }
}
