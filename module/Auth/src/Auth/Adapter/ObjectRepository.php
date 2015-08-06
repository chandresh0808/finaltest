<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Auth\Adapter;
  
use DoctrineModule\Authentication\Adapter\ObjectRepository as BaseObjectRepository;
use Zend\Authentication\Result as AuthenticationResult;
 
class ObjectRepository extends BaseObjectRepository
{
    /**
     * {@inheritDoc}
     */
    public function authenticate()
    {
        $this->setup();
        $options = $this->options;
        $identity = $options
            ->getObjectRepository()
            ->findOneBy(
                array(
                    $options->getIdentityProperty() => $this->identity,
                    'deleteFlag' => 0,  
                )
            );
        if (!$identity) {
            $this->authenticationResultInfo['code'] = AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND;
            $this->authenticationResultInfo['messages'][] = 'A record with the supplied identity could not be found.';

            return $this->createAuthenticationResult();
        }
        $authResult = $this->validateIdentity($identity);
 
        return $authResult;
    }
}

