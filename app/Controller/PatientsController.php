<?php


App::uses('AppController', 'Controller');


class PatientsController extends AppController
{
    public $components = array('GoogleApi');
    public $uses = array('Patient', 'Appointment', 'Doctor');
    
    public function isAuthorized() {
        
        $user = $this->Auth->user();
        $userType = strtolower($user['user_type']);
        if ($userType != USER_TYPE_PATIENT) {
            return false;
        }
        
        return true;
    }


    public function index()
	{
        
        $this->Appointment->bindModel(
                 array('belongsTo' => array(
               'Doctor' => array(
                    'className' => 'User',
                    'foreignKey' => 'doctor_id'
                 )
            )
        ));
        
		$user = $this->Auth->user();
        $userId = $user['id'];
        
        $paginate = array(
            'limit' => 10,
            'order' => array('Appointment.appointment_time' => 'desc'),
            
        );
        $this->Paginator->settings = $paginate;

		$this->set('appointments',$this->Paginator->paginate('Appointment',
        array('Appointment.patient_id'=>$userId)
                ));
	}


	public function add()
	{
		if($this->request->is('post')) {
            
			$this->Appointment->create();
            $currentUser = $this->Auth->user();
            $data = $this->request->data;
            
            $data['Appointment']['patient_id'] = $currentUser['id'];
            
			if($this->Appointment->save($data)) {
				$this->Session->setFlash(__('Your appointment has been saved.'));
                return $this->redirect(array('action' => 'index'));
			} 
			$this->Session->setFlash(__('Unable to add appointment'));
		}
        
        $doctorList = $this->Doctor->find('list', array('fields' => array('Doctor.user_id', 'Doctor.first_name')));
        $this->set('doctorList', $doctorList);
		
		
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
            $this->request->data['Appointment']['id'] = $id;
            $this->request->data['Appointment']['status'] = STATUS_PENDING;
	        if ($this->Appointment->save($this->request->data)) {
	            $this->Session->setFlash(__('Your appointment has been updated.'));
	            return $this->redirect(array('action' => 'index'));
	        }
	        $this->Session->setFlash(__('Unable to update your appointment.'));
	    }

	    if (!$this->request->data) {
	        $this->request->data = $appointment;
	    }
        
        $doctorList = $this->Doctor->find('list', array('fields' => array('Doctor.user_id', 'Doctor.first_name')));
        $this->set('doctorList', $doctorList);
        
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
