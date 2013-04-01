<?php

namespace Todo\Model;

use Application\Model\AbstractModel;

class Task extends AbstractModel
{
    public $id;
    public $uid;
    public $user_id;
    public $priority;
    public $title;
    public $description;
    public $deadline;
    
    public function isOverdue()
    {
        return (strtotime($this->deadline) < time());
    }
}
