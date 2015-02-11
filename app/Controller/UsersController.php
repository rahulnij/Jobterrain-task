<?php


App::uses('AppController', 'Controller');


class UsersController extends AppController
{
    public $uses = array('User', 'Patient', 'Doctor');
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'login','storeToken');
    }
    
    public function index()
	{
       $this->Session->write('googleState', md5(rand()));
	}
    
    public function login()
    {
         
        if ($this->Auth->login()) {
            $user = $this->Auth->user();
            
            $userType = strtolower($user['User']['user_type']);
            if (in_array($userType, array('patient', 'doctor'))) {
                switch ($userType) {
                    case 'patient': $this->redirect(array('controller'=> 'patients', 'action' => 'index'));
                                    break;
                    case 'doctor' : $this->redirect(array('controller'=> 'doctors', 'action' => 'index'));
                                    break;
                }
                
            }
            
             $this->redirect(array('controller'=> 'users', 'action' => 'nextStep'));
            
        }
        $this->Session->setFlash(__('Please login'));
       // $this->redirect(array('controller'=> 'users', 'action' => 'index'));
    }
    
    public function logout()
    {   
        //$this->Session->destroy();
        $this->redirect($this->Auth->logout());
    }
    
    public function storeToken()
    {
        $response = array(
            'error' => false,
            'msg' => 'Unable to login.'
        );
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $code = $this->request->data['code'];
            //echo $accessToken = $this->request->data['access_token'];exit;
            $client = new Google_Client();
            $client->setClientId(GOOGLE_CLIENT_ID);
            $client->setClientSecret(GOOGLE_CLIENT_SECRET);
            $client->setRedirectUri('postmessage');
            $client->setAccessType('offline');
            $client->authenticate($code);

            $accessToken = $client->getAccessToken();
            $accessTokenObj = json_decode($accessToken);
                    
            $plus = new Google_Service_Plus($client);
            $client->setAccessToken($accessToken);
            $refreshToken = $client->getRefreshToken();

            $googlePlusInfo = $plus->people->get('me');
            $googleId = $googlePlusInfo['id'];
            $googleLName = $googlePlusInfo['modelData']['name']['familyName'];
            $googleFName = $googlePlusInfo['modelData']['name']['givenName'];
            
            
           
            $data = array(
               'google_id' => $googleId,
                'first_name' => $googleFName,
                'last_name' => $googleLName,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                
            );
            $user = $this->User->findByGoogleId($googleId);
            
            if (!$user) {
                $this->User->create();
                $this->User->save($data);
                $user = $this->User->read();
                
            } 
            
            $this->request->data['User'] = $user;
            $this->Auth->login($this->request->data['User']);
            
            $response['error'] = true;
            
        }
        return json_encode($response);
    }

    
    public function nextStep()
    {
        
        if ($this->request->is('post')) {
            $user = $this->Auth->user();
            $userType = $this->request->data['user_type'];
            var_dump($userType);
            $userId = $user['User']['id'];
            var_dump($userId);
            $firstName = $user['User']['first_name'];
            $lastName = $user['User']['last_name'];
            
            $user['User']['user_type'] = $userType;
            
            $this->Session->write('user', $user);
            $this->User->id = $userId;
            $this->User->save($user['User']);
            $this->request->data['User'] = $user;
            
            // adding patient/doctor depend on user type
            $data = array(
                'user_id' => $userId,
                'first_name' => $firstName,
                'last_name' => $lastName
            );
            switch (strtolower($userType)) {
                case 'patient': $this->Patient->save($data);
                                break;
                case 'doctor' : $this->Doctor->save($data);
                                break;
            }
            
            
            if ($this->Auth->login($this->request->data['User'])) {
                $this->Session->setFlash('You are successfully logged in');
                return $this->redirect($this->Auth->redirect());
            
            } else {
                $this->Session->setFlash('Failed to login');
            }
            
            
        }
        
        
    }

}
