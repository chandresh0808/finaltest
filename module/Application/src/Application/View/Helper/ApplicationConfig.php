<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ApplicationConfig extends AbstractHelper
{

    public function __construct($config)
    {
        $this->key = $config;
    }

    public function __invoke()
    {
        return $this->key;
    }

}

?>
