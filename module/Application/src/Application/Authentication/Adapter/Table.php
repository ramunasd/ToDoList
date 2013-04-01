<?php
namespace Application\Authentication\Adapter;

use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class Table extends AuthAdapter
{
    public function __construct(DbAdapter $zendDb)
    {
        parent::__construct($zendDb, 'users', 'username', 'password', 'MD5(?)');
    }
}
