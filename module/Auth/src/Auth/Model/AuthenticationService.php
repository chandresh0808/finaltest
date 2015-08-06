<?php

namespace Auth\Model;

class AuthenticationService extends \Application\Model\AbstractCommonServiceMutator {                 
    
    /*
     * Authenticates the user using doctrine adapter
     * @param array $dataList
     * 
     * @return object $result
     */
    public function authenticate($dataList)
    {
        $adapter = $this->getAuthenticationAdapterService()->getAdapter();
        $adapter->setIdentityValue($dataList['email']);
        $adapter->setCredentialValue(md5($dataList['password']));
        $result = $this->getAuthenticationAdapterService()->authenticate();
        return $result;
    }
    
    /**
     * Magic method which helps this class act as wrapper for the zfc auth server
     * 
     * @param string $method
     * @param array $arguments
     * 
     * @return miscellanous
     */
    public function __call($method, $arguments) 
    {
        if (method_exists($this->getAuthenticationAdapterService(), $method)) {            
            return $this->getAuthenticationAdapterService()->$method($arguments);
        }
        throw new Exception('Method does not exist');
    }
       
}
