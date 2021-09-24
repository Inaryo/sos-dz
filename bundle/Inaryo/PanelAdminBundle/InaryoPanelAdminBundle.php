<?php
namespace Inaryo\PanelAdminBundle\src;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class InaryoPanelAdminBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}

?>