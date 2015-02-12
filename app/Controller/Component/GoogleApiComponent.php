<?php

App::uses('Component', 'Controller');
class GoogleApiComponent extends Component
{
   
   private $client = null;
   public function __construct(\ComponentCollection $collection, $settings = array()) {
       parent::__construct($collection, $settings);
       
       $this->client = new Google_Client();
       $this->client->setClientId(GOOGLE_CLIENT_ID);
       $this->client->setClientSecret(GOOGLE_CLIENT_SECRET);
       
   }
   public function googleEvent()
    {
        
       
        //$googleClient->s
    }
    
    private function getCalandarService()
    {
        // have to change to get all time one instance instead of creating every time
       $this->client->addScope('https://www.googleapis.com/auth/calendar');
       $service = new Google_Service_Calendar($this->client);
       return $service;
    }
    public function createCalandar(Array $data)
    {
        
        $service = $this->getCalandarService();
        $accessToken = $data['accessToken'];
        $this->client->setAccessToken($accessToken);
        
        $calendar = new Google_Service_Calendar_Calendar();
        $calendar->setSummary($data['summary']);
        $createdCalendar = $service->calendars->insert($calendar);

        return $createdCalendar->getId();
        
    }
    
    public function isCalendarExist($calandarId)
    {
        return true;
    }
    
    public function createEvent($data)
    {
    
        $event = new Google_Service_Calendar_Event();
        
        $event->setSummary('Appointment');
        $event->setLocation('Somewhere');
        $start = new Google_Service_Calendar_EventDateTime();
        $start->setDateTime('2015-06-03T10:00:00.000-07:00');
        $event->setStart($start);
        $end = new Google_Service_Calendar_EventDateTime();
        $end->setDateTime('2015-06-03T10:25:00.000-07:00');
        $event->setEnd($end);
        $attendee1 = new Google_Service_Calendar_EventAttendee();
        $attendee1->setEmail('attendeeEmail');
        
        $attendees = array($attendee1,
                          
                          );
        $event->attendees = $attendees;
        $service = $this->getCalandarService();
        $createdEvent = $service->events->insert('primary', $event);

        return $createdEvent->getId();
        
    }
    
}