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
            
            $userType = strtolower($user['user_type']);
            if (in_array($userType, array(USER_TYPE_PATIENT, USER_TYPE_DOCTOR))) {
                switch ($userType) {
                    case USER_TYPE_PATIENT: //var_dump(array('controller'=> 'patients', 'action' => 'index'),$this->Auth->user());exit;
                                   return $this->redirect(array('controller'=> 'patients', 'action' => 'index'));
                                    break;
                    case USER_TYPE_DOCTOR : return $this->redirect(array('controller'=> 'doctors', 'action' => 'index'));
                                    break;
                }
                
            }
            
             $this->redirect(array('controller'=> 'users', 'action' => 'nextStep'));
            
        }
        
        $this->redirect(array('controller'=> 'users', 'action' => 'index'));
    }
    
    public function logout()
    {   
        $this->Session->setFlash(__('You successfully logout'));
        $this->redirect($this->Auth->logout());
    }
    
    /**
     * create user and login to site from posted google authenticate code
     * 
     * @return json object {error, msg}
     */
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
            //$accessTokenObj = json_decode($accessToken);
                    
            $plus = new Google_Service_Plus($client);
            $client->setAccessToken($accessToken);
            $refreshToken = $client->getRefreshToken();

            $googlePlusInfo = $plus->people->get('me');
            
            $googleId = $googlePlusInfo['id'];
            $googleLName = $googlePlusInfo['modelData']['name']['familyName'];
            $googleFName = $googlePlusInfo['modelData']['name']['givenName'];
            $googleEmail = '';
            if ($googlePlusInfo['modelData']['emails']) {
                foreach ($googlePlusInfo['modelData']['emails'] as $email) {
                    if ($email['type'] == 'account')
                    {
                        $googleEmail = $email['value'];
                    }
                }
            }
            
            
           
            $data = array(
               'google_id' => $googleId,
                'google_email' => $googleEmail,
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
                
            } else {
                
                $user['User']['access_token'] = $accessToken;
                $this->User->save($user['User']);
                $user = $this->User->read();
                
            }
            
            $this->request->data = $user;
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
            $userId = $user['id'];
            $firstName = $user['first_name'];
            $lastName = $user['last_name'];
            $user['user_type'] = $userType;
            $this->Session->write('user', $user);
            $this->User->id = $userId;
            $this->User->save($user);
            $this->request->data['User'] = $user;
            
            // adding patient/doctor depend on user type
            $data = array(
                'user_id' => $userId,
                'first_name' => $firstName,
                'last_name' => $lastName
            );
            switch (strtolower($userType)) {
                case USER_TYPE_PATIENT: $this->Patient->save($data);
                                break;
                case USER_TYPE_DOCTOR : $this->Doctor->save($data);
                                        $doctor = $this->Doctor->read();
                                        $doctor['Doctor']['doctor_code'] = 'D-'.$doctor['Doctor']['id'];
                                        
                                        $this->Doctor->save($doctor);
                                break;
            }
            
            
            if ($this->Auth->login($this->request->data['User'])) {
                $this->Session->setFlash('You are successfully logged in');
                return $this->redirect($this->Auth->redirectUrl());
            
            } else {
                $this->Session->setFlash('Failed to login');
            }
            
            
        }
        
        
    }

}
