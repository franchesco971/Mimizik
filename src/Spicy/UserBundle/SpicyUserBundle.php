<?php

namespace Spicy\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpicyUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
