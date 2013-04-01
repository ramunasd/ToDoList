<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $auth = $this->getServiceLocator()->get('AuthService');
        if(!$auth->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }else{
            return $this->redirect()->toRoute('todo');
        }
    }
}
