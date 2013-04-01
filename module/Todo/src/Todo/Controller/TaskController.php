<?php
namespace Todo\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\Mvc\MvcEvent;
use Todo\Form,
    Todo\Model;

class TaskController extends AbstractActionController
{
    /**
     * @var Todo\Model\TasksTable
     */
    protected $_tasksTable;
    
    protected $_acceptCriteria = array(
        'Zend\View\Model\JsonModel' => array(
            'application/json',
        ),
    );
    
    public function onDispatch(MvcEvent $e)
    {
        parent::onDispatch($e);
        $action = $e->getRouteMatch()->getParam('action', 'not-found');
        $service = $this->getServiceLocator()->get('AclService');
        if(!$service->isAllowed('mvc:task', $action)){
            $response = $this->notFoundAction();
            $e->setResult($response);
            return $response;
        }
    }
    
    public function addAction()
    {
        $form = new Form\Task;
        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $task = new Model\Task;
                $task->exchangeArray($form->getData());
                $this->_getTasksTable()->save($task);
                // Redirect to list
                return $this->redirect()->toUrl($request->getHeaders('referer')->getUri());
            }
        }
        $view = $this->_getViewModel();
        $view->setVariable('form', $form);
        $view->setVariable('targetAction', 'add');
        $view->setVariable('targetId', null);
        return $view;
    }

    public function editAction()
    {
        $view = $this->_getViewModel();
        $uid = $this->params()->fromRoute('id');
        try{
            $task = $this->_getTasksTable()->get($uid);
        }catch(Exception $e){
            return $view;
        }
        
        $form = new Form\Task;
        $form->bind($task);
        
        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $task = new Model\Task;
                $task->exchangeArray($form->getData());
                $this->_getTasksTable()->save($task);
                // Redirect to list
                return $this->redirect()->toUrl($request->getHeaders('referer')->getUri());
            }
        }
        
        $view->setVariable('form', $form);
        $view->setVariable('targetAction', 'edit');
        $view->setVariable('targetId', $uid);
        $view->setTemplate('todo/task/add');
        return $view;
    }
    
    public function completeAction()
    {
        $viewModel = $this->acceptableViewModelSelector($this->_acceptCriteria);
        $uid = $this->params()->fromRoute('id');
        try{
            $task = $this->_getTasksTable()->get($uid);
        }catch(Exception $e){
            
        }
        $task->completed_at = date('Y-m-d H:i:s');
        $this->_getTasksTable()->save($task);
        $viewModel->setVariables(array(
            'success' => true,
            'uid' => $uid,
        ));
        return $viewModel;
    }
    
    protected function _getTasksTable()
    {
        if (!$this->_tasksTable) {
            $sm = $this->getServiceLocator();
            $this->_tasksTable = $sm->get('Todo\Model\TasksTable');
        }
        return $this->_tasksTable;
    }
    
    protected function _getViewModel()
    {
        $viewModel = new ViewModel;
        $request = $this->getRequest();
        $viewModel->setTerminal($request->isXmlHttpRequest());
        return $viewModel;
    }
}
