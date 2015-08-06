<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetIdentity extends AbstractHelper
{

    public function __construct($identity)
    {
        $this->identity = $identity;
    }

    public function __invoke()
    {
        return $this->identity;
    }

}

?>
