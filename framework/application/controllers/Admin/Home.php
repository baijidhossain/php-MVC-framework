<?php

class Home extends Controller {

    public function __construct()
    {
        $this->model = $this->loadModel('Home');
    }

    public function Index()
    {
        $data = [
            'page_title' => 'Dashboard'
        ];

        $this->view('Home', $data);

    }

}