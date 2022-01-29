<?php

namespace TLBM;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;

class MainFactory
{
    /**
     * @param string $class
     * @param array $parameters
     *
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function create(string $class, array $parameters = []) {
        /**
         * @global Container $TLBM_DICONTAINER
         */
        global $TLBM_DICONTAINER;

        if($TLBM_DICONTAINER) {
            return $TLBM_DICONTAINER->make($class, $parameters);
        }

        return null;
    }
}