<?php


App::uses('AppController', 'Controller');


class DoctorsController extends AppController
{
    public $components = array('GoogleApi');
    public $uses = array('Doctor', 'Appointment','User');
    
    
    public function isAuthorized() {
        parent::isAuthorized();
        $user = $this->Auth->user();
        $userType = strtolower($user['user_type']);
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
        $userId = $user['id'];
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
                if ($this->request->data['Appointment']['status'] == 'Approved') {
                    $this->googlEvent($id);
                }
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

    /**
     *  create event on user calendar
     */
    public function googleEvent($appointmentId)
    {
        $calandarId = $this->Auth->user('calandar_id');
        
        // create calandar not exist both in user account here or on google.
        if (is_null($calandarId) || $this->GoogleApi->isCalendarExist()) {
            $data['summary'] = 'Job terrain task';
            $data['accessToken'] = $this->Auth->user('access_token');
            $calandarId = $this->GoogleApi->createCalandar($data);
            // update current user calandar id
            $user = $this->Auth->user();
            $user['calandar_id'] = $calandarId;
            $this->User->save($user);
            // udating current auth data
            $this->Auth->user($user);
        }
        
     
     
        //$googleClient->s
    }
    
    
}
