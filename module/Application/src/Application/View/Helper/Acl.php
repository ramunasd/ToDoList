<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Acl\Acl as PermisionsAcl,
    Application\Model\User;

class Acl extends AbstractHelper
{
    protected $_acl;
    protected $_role;
    
    public function __construct(PermisionsAcl $acl, User $user)
    {
        $this->_acl = $acl;
        $this->_role = $user->role;
    }
    
    public function __invoke($resource, $action)
    {
        return $this->_acl->isAllowed($this->_role, $resource, $action);
    }
}
