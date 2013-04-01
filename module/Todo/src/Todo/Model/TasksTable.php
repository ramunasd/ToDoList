<?php

namespace Todo\Model;

use Zend\Db\TableGateway\TableGateway;

class TasksTable
{
    /**
     * @var TableGateway
     */
    protected $_tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->_tableGateway = $tableGateway;
    }

    public function fetchAll($type = 'all', $limit = 30, $offset = 0)
    {
        $sql = $this->_tableGateway->getSql()->select();
        $sql->where(array('completed_at' => null));
        switch($type){
            case 'today':
                $sql->where(array('deadline <= ?' => date('Y-m-d', strtotime('+1 day'))));
                break;
            case 'overdue':
                $sql->where(array('deadline < ?' => date('Y-m-d H:i:s')));
                break;
            case 'important':
                $sql->where(array('priority' => array('high', 'critical')));
                break;
            case 'all':
            default:
        }
        $sql->limit($limit)
            ->offset($offset);
        $sql->order('deadline ASC');
        return $this->_tableGateway->selectWith($sql);
    }
    
    /**
     * Get task by id
     * 
     * @param integer $id
     * @return Task
     */
    public function find($id)
    {
        $rowset = $this->_tableGateway->select(array('id' => $id));
        return (count($rowset)) ? $rowset->current() : false;
    }

    public function get($uid)
    {
        $rowset = $this->_tableGateway->select(array('uid' => $uid));
        $row = $rowset->current();
        return $row ? $row : false;
    }
    
    public function add(Task $task)
    {
        
    }

    public function save(Task $task)
    {
        $data = $task->getArrayCopy();
        $id = (int)$task->id;
        if ($id == 0) {
            do{
                $uid = substr(md5(microtime() . rand()), 0, 8);
            }while($this->get($uid));
            $data['uid'] = $uid;
            $this->_tableGateway->insert($data);
        } else {
            if ($this->find($id)) {
                $this->_tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Task does not exist');
            }
        }
    }

    public function delete($uid)
    {
        $this->_tableGateway->delete(array('uid' => $uid));
    }
}
