<?php

namespace Application\Model;

abstract class AbstractModel
{
    /**
     * Populate data from array
     * 
     * @param array $data
     * @return AbstractModel
     */
    public function exchangeArray($data)
    {
        foreach($data as $key => $value){
            if(!property_exists($this, $key) || $key{0} == '_'){
                continue;
            }
            $this->$key = $value;
        }
        return $this;
    }
    
    /**
     * Get entity properties as array
     * 
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
