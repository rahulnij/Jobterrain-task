<?php


App::uses('AppController', 'Controller');


class PatientsController extends AppController
{
    public $uses = array('Patient', 'Appointment');
    
    public function isAuthoried() {
        parent::isAuthoried();
        $user = $this->Auth->user();
        $userType = strtolower($user['User']['user_type']);
        if ($userType != 'patient') {
            return false;
        }
        
        return true;
    }


    public function index()
	{
        
        $this->Appointment->bindModel(
                 array('belongsTo' => array(
                'Doctor' => array(
                    'className' => 'Doctor'
                ),
                'Patient' => array(
                    'className' => 'Patient'
                )
            )
        ));
        
		$patientId = 1;
        $paginate = array(
            'limit' => 1,
            'order' => array('Appointment.appointmentTime' => 'desc'),
            
        );
        $this->Paginator->settings = $paginate;

		$this->set('appointments',$this->Paginator->paginate('Appointment',
        array('Appointment.patient_id'=>$patientId)
                ));
	}

    
//	public function view($id = null)
//	{
//		if(!$id) {
//			throw new NotFoundException(__('Invalid appointment'));
//		}
//
//		$appointment = $this->Appointment->findById($id);
//
//		if(!$appointment) {
//			throw new NotFoundException(__('Invalid appointment'));
//			
//		}
//
//		$this->set('appointment', $appointment);
//	}

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
		//$this->Session->setFlash(__('hi<br/>'), 'flash_custom', array('flashVar'=> 'hello','test'));
		
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
