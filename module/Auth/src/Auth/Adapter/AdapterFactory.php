<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Auth\Adapter;
 
use DoctrineModule\Service\Authentication\AdapterFactory as BaseAdapterFactory;
 
use Auth\Adapter\ObjectRepository;
use Zend\ServiceManager\ServiceLocatorInterface;
 
class AdapterFactory extends BaseAdapterFactory
{
    /**
     * {@inheritDoc}
     *
     * @return \MyDoctrineAuth\Adapter\ObjectRepository
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options \DoctrineModule\Options\Authentication */
        $options = $this->getOptions($serviceLocator, 'authentication');
 
        if (is_string($objectManager = $options->getObjectManager())) {
            $options->setObjectManager($serviceLocator->get($objectManager));
        }
 
        return new ObjectRepository($options);
    }
}