<?php


App::uses('AppController', 'Controller');


class DoctorsController extends AppController
{
    public $components = array('GoogleApi');
    public $uses = array('Doctor', 'Appointment');
    
    
    public function isAuthoried() {
        parent::isAuthoried();
        $user = $this->Auth->user();
        $userType = strtolower($user['User']['user_type']);
        if ($userType != 'doctor') {
            return false;
        }
        
        return true;
    }

    
    public function index()
	{
        $this->Appointment->bindModel(
                 array('belongsTo' => array(
                 'User' => array(
                    'className' => 'User',
                    'foreignKey' => 'patient_id'
                 )
            )
        ));
        
		$user = $this->Auth->user();
        $userId = $user['User']['id'];
        $paginate = array(
            'limit' => 10,
            'order' => array('Appointment.appointmentTime' => 'desc'),
            
        );
        $this->Paginator->settings = $paginate;

		$this->set('appointments',$this->Paginator->paginate('Appointment',
        array('Appointment.doctor_id'=>$userId)
                ));
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

}
