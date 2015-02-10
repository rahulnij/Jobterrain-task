<?php


App::uses('AppController', 'Controller');


class UsersController extends AppController
{
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'nextStep', 'login','storeToken');
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
                'access_token' => $accessTokenObj->access_token,
                'refresh_token' => $refreshToken,
                'token_created' => $accessTokenObj->created

            );
            $user = $this->User->findByGoogleId($googleId);
            
            if (!$user) {
                $this->User->create();
                $this->User->save($data);
                $user = $this->User->read();
                
            } 
            
            $this->request->data['User'] = $user;
            $this->Auth->login($this->request->data['User']);
            
            echo 'true';
            exit;
        }

    }

    
    public function nextStep()
    {
       
        if ($this->request->is('post')) {
            $user = $this->Session->read('user');
            $user['User']['user_type'] = $this->request->data['user_type'];
            $this->Session->write('user', $user);
            $this->User->id = $user['User']['id'];
            $this->User->save($user['User']);
            $this->request->data['User'] = $user;
             
            if ($this->Auth->login($this->request->data['User'])) {
                $this->redirect($this->Auth->redirect());
                $this->Session->setFlash('You are successfully logged in');
            } else {
                $this->Session->setFlash('Failed to login');
            }
            exit;
           // $this->redirect(array('controller'=> 'users', 'action'=> 'index'));
            
        }
        
    }

}
