<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\Db\TableGateway\TableGateway;

use Application\Form\Login as FormLogin;

class UserController extends AbstractActionController
{
    /**
     * @var Zend\Authentication\AuthenticationService
     */
    protected $_authService;
    
    /**
     * @var Application\Authentication\Storage\Session
     */
    protected $_storage;
    
    public function loginAction()
    {
        //if already login, redirect to success page 
        if ($this->_getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('todo');
        }
                 
        $form = new FormLogin;
         
        return array(
            'form'      => $form,
            'messages'  => $this->flashmessenger()->getMessages(),
        );
    }
    
    public function authenticateAction()
    {
        $form = new FormLogin;
        $redirect = 'login';
        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if ($form->isValid()){
                //check authentication...
                $username = $request->getPost('username');
                $this->_getAuthService()->getAdapter()
                                       ->setIdentity($username)
                                       ->setCredential($request->getPost('password'));
                                        
                $result = $this->_getAuthService()->authenticate();
                foreach($result->getMessages() as $message){
                    //save message temporary into flashmessenger
                    $this->flashmessenger()->addMessage($message);
                }
                 
                if ($result->isValid()) {
                    $redirect = 'todo';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1 ) {
                        $this->_getSessionStorage()->setRememberMe(1);
                        //set storage again 
                        $this->_getAuthService()->setStorage($this->_getSessionStorage());
                    }
                    $usersTable = $this->getServiceLocator()->get('UsersTableGateway');
                    $rows = $usersTable->select(array('username' => $username));
                    $user = $rows->current();
                    $this->_getAuthService()->getStorage()->write($user);
                }
            }
        }
        return $this->redirect()->toRoute($redirect);
    }
    
    public function logoutAction()
    {
        $this->_getSessionStorage()->forgetMe();
        $this->_getAuthService()->clearIdentity();
         
        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }
    
    protected function _getAuthService()
    {
        if(!$this->_authService) {
            $this->_authService = $this->getServiceLocator()->get('AuthService');
        }
        return $this->_authService;
    }
    
    protected function _getSessionStorage()
    {
        if(!$this->_storage){
            $this->_storage = $this->getServiceLocator()->get('Application\Authentication\Storage\Session');
        }
        return $this->_storage;
    }
}
