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
    
    public function createCalandar(Array $data)
    {
        $this->client->addScope('https://www.googleapis.com/auth/calendar');
        $service = new Google_Service_Calendar($this->client);
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
    
    
}