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
        if ($userType != USER_TYPE_DOCTOR) {
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
            'limit' => DEFAULT_PAGE_SIZE,
            'order' => array('Appointment.appointment_time' => 'asc'),
            
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
        
        if ($appointment['Appointment']['status'] == STATUS_APPROVED) {
            $this->redirect(array('controller' => 'doctors', 'action'=> 'index'));
        }
        
	    if ($this->request->is(array('post', 'put'))) {
	        
	        $this->Appointment->id = $id;
	        if ($this->Appointment->save($this->request->data)) {
                if ($this->request->data['Appointment']['status'] == STATUS_APPROVED) {
                    $this->googleEvent($id);
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
        $this->Appointment->bindModel(
                 array('belongsTo' => array(
                 'Patient' => array(
                    'className' => 'User',
                    'foreignKey' => 'patient_id'
                 ),
                'Doctor' => array(
                    'className' => 'User',
                    'foreignKey' => 'doctor_id'
                 )
            )
        ));
        
        $appointment = $this->Appointment->read(null, $appointmentId);
        $patient = $appointment['Patient'];
        $doctor = $appointment['Doctor'];
        
        
        
        $appointmentTime = new DateTime($appointment['Appointment']['appointment_time']);
        $patientName = $appointment['Patient']['first_name'];
        $doctorName = $appointment['Patient']['first_name'];
        $patient['summary'] = 'Appointment With '. $patientName;
        $doctor['summary'] = 'Appointment With '. $doctorName;
        $users[] = $patient;
        $users[] = $doctor;
        
        foreach ($users as $user ) {
            // create calendar not exist both in user account here or on google.
            if (is_null($user['calendar_id']) || $this->GoogleApi->isCalendarExist($user['calendar_id']) === false) {
                $data['summary'] = 'Job terrain task';
                $data['accessToken'] = $user['access_token'];
                $calendarId = $this->GoogleApi->createCalendar($data);
                // update current user calendar id

                $user['calendar_id'] = $calendarId;
                $this->User->save($user);

            }
            
            

            $eventData = array(
                'summary' => $user['summary'],
                'startTime' => $appointmentTime->format(DateTime::RFC3339),
                'endTime' => $appointmentTime->format(DateTime::RFC3339),
                'calendarId' => $user['calendar_id']
            );
            
            $this->GoogleApi->setAccessToken($user['access_token']);
            $this->GoogleApi->createEvent($eventData);
            
        }
        
        
    }
    
    
}
