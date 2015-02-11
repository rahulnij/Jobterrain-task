<?php

App::uses('AppModel', 'Model');
class Appointment extends AppModel {
    public $name = 'Appointment';
    public $validate = array(
        'doctor_id' => array(
            'doctorid' => array(
                'rule'=> 'notEmpty',
                'message' => 'Doctor is required',
                'allowEmpty' => false
            )
        ),
        'appointment_time' => array(
            
            'appointmentTimeFormat' => array(
                'rule' => array('datetime', 'ymd'),
                'message' => 'Date format error'
            ),
            'appointmentTimeAvailable' => array(
                'rule'=> 'checkTimeAvailability',
                'message' => 'Appointment time already assign to other patient.',
                'allowEmpty' => false
            ),
            'timeChange' => array(
                'on' => 'update',
                'rule' => 'timeChange',
                'message'=> 'Please update new time'
            )
        )
    );
    
    public function checkTimeAvailability($check) {
        // check for concurrency request to same doctor and at same time
            $row = $this->find('count', array(
                
                'conditions' => array(
                    'appointment_time' => $this->data[$this->name]['appointment_time'],
                    'doctor_id' => $this->data[$this->name]['doctor_id']
                )
            ));

        
            if ($row > 0) {
                
                return false;
            }

            
            return true;
   }
   
    public function timeChange($check) {
            
         $row = $this->find('count', array(
                
                'conditions' => array(
                    'appointment_time' => $this->data[$this->name]['appointment_time'],
                    'id' => $this->data[$this->name]['id']
                )
            ));

            if ($row > 0) {
                
                return false;
            }

            
            return true;
   }
   
   
}

