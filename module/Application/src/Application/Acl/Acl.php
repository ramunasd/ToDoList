<?php

namespace Application\Acl;

use Zend\Permissions\Acl\Acl as BaseAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource;

class Acl extends BaseAcl
{
    public function __construct()
    {
        $this->addRole(new Role('guest'));
        $this->deny('guest');
        
        $this->addRole(new Role('worker'));
        $this->addRole(new Role('admin'));
        
        $this->addResource(new GenericResource('mvc:task'));
        
        $this->allow('worker', 'mvc:task', array('complete', 'index', 'today', 'overdue', 'important', 'complete'));
        $this->allow('admin');
    }
}
