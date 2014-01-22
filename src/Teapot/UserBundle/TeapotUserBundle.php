<?php

namespace Teapot\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TeapotUserBundle extends Bundle
{
    public function getParent()
    {
        return 'TeapotBaseUserBundle';
    }
}
