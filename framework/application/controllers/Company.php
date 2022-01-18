<?php

class Company extends Controller {

     public function __construct()
    {
        $this->model = $this->loadModel('Company');
    }

    public function Index()
    {
echo 'Nazrul Islam';
// $data = ['page_title' => 'Home'];

//$this->view('Home', $data);
    }


public function About()
    {
echo 'This is About Page';
    }


public function Detail($id)
    {
		$data['company_name'] = 'Alpha Net';
		$data['company_phone'] = '01979547393';
		$data['company_email'] = 'info@alpha.net.bd';

		$this->view('Company', $data);
        
    }


}