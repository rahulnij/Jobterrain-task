<?php


App::uses('AppController', 'Controller');


class PatientsController extends AppController
{
    public $helpers = array('Html', 'Form', 'Session');
	public $components = array('Session');
    public $uses = array('Appointment');
    public function index()
	{
		

		$this->set('appointments',$this->Appointment->find('all'));
	}

	public function view($id = null)
	{
		if(!$id) {
			throw new NotFoundException(__('Invalid appointment'));
		}

		$appointment = $this->Appointment->findById($id);

		if(!$appointment) {
			throw new NotFoundException(__('Invalid appointment'));
			
		}

		$this->set('appointment', $appointment);
	}

	public function add()
	{
		if($this->request->is('post')) {

			$this->Appointment->create();
			if($this->Appointment->save($this->request->data)) {
				$this->Session->setFlash(__('Your appointment has been saved.'));
                return $this->redirect(array('action' => 'index'));
			} 
			$this->Session->setFlash(__('Unable to add appointment'));
		}
		
		
	}

	public function edit($id = null)
	{

		$this->autoRender = false;
	    if (!$id) {
	        throw new NotFoundException(__('Invalid appointment'));
	    }

	    $appointment = $this->Appointment->findById($id);
	    if (!$appointment) {
	        throw new NotFoundException(__('Invalid appointment'));
	    }

	    if ($this->request->is(array('post', 'put'))) {
	        
	        $this->Appointment->id = $id;
	        if ($this->Appointment->save($this->request->data)) {
	            $this->Session->setFlash(__('Your appointment has been updated.'));
	            return $this->redirect(array('action' => 'index'));
	        }
	        $this->Session->setFlash(__('Unable to update your appointment.'));
	    }

	    if (!$this->request->data) {
	        $this->request->data = $appointment;
	    }
	    $this->render('edit');

	}

	public function delete($id) {
	    if ($this->request->is('get')) {
	        throw new MethodNotAllowedException();
	    }

	    if ($this->Appointment->delete($id)) {
	        $this->Session->setFlash(
	            __('The appoinment with id: %s has been deleted.', h($id))
	        );
	        return $this->redirect(array('action' => 'index'));
	    }
	}

}
