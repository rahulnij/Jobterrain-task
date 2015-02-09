<?php


App::uses('AppController', 'Controller');


class GoogleController extends AppController
{
    public $helpers = array('Html', 'Form', 'Session','Paginator');
	public $components = array('Session','Paginator');
    public $uses = array('Patient', 'Appointment');
    public function index()
	{
       $this->Session->write('googleState', md5(rand()));
	}

    

}
