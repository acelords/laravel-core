<?php

namespace AceLords\Core\Library\Contracts;

interface SidebarsInterface
{
    /**
     * return sidebar entries
     *
     * @return array
     */
    public function data() : array;
    
}
