<?php
namespace Tests\Helper;

use CPC\ServerMonitor\Helper;

class ResourceTraitImplementor
{
    use Helper\Traits\Resource;

    public function proxyGetResourceName()
    {
        return $this->getResourceName();
    }
}
