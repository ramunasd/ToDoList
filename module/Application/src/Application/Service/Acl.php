<?php

namespace Application\Service;

use Application\Acl\Acl as BaseAcl,
    Application\Model\User;

class Acl
{
    protected $_acl;
    protected $_role;
    
    public function __construct(BaseAcl $acl, $user = null)
    {
        $this->_acl = $acl;
        if($user instanceof User){
            $this->_role = $user->role;
        }else{
            $this->_role = 'guest';
        }
    }
    
    public function isAllowed($resource, $privilege = null)
    {
        return $this->_acl->isAllowed($this->_role, $resource, $privilege);
    }
}
