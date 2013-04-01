<?php
namespace Todo\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $table = $this->getServiceLocator()->get('Todo\Model\TasksTable');
        $view = new ViewModel(array(
            'tasks' => $table->fetchAll(),
        ));
        $view->setTemplate('todo/task/list');
        return $view;
    }
    
    public function todayAction()
    {
        $table = $this->getServiceLocator()->get('Todo\Model\TasksTable');
        $view = new ViewModel(array(
            'tasks' => $table->fetchAll('today'),
        ));
        $view->setTemplate('todo/task/list');
        return $view;
    }
    
    public function overdueAction()
    {
        $table = $this->getServiceLocator()->get('Todo\Model\TasksTable');
        $view = new ViewModel(array(
            'tasks' => $table->fetchAll('overdue'),
        ));
        $view->setTemplate('todo/task/list');
        return $view;
    }
    
    public function importantAction()
    {
        $table = $this->getServiceLocator()->get('Todo\Model\TasksTable');
        $view = new ViewModel(array(
            'tasks' => $table->fetchAll('important'),
            'template' => 'todo/task/index',
        ));
        $view->setTemplate('todo/task/list');
        return $view;
    }
}
