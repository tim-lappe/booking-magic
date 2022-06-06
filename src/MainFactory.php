<?php

namespace TLBM;

use DI\Container;
use Throwable;

class MainFactory
{
    /**
     * @template T
     * @param class-string<T> $class
     * @param array $parameters
     *
     * @return ?T
     */
    public static function create(string $class, array $parameters = []) {
        try {
            /**
             * @global Container $TLBM_DICONTAINER
             */ global $TLBM_DICONTAINER;

            if ($TLBM_DICONTAINER) {
                return $TLBM_DICONTAINER->make($class, $parameters);
            }
        } catch (Throwable $exception) { }

        return null;
    }

    /**
     * @template T
     * @param class-string<T> $class
     *
     * @return ?T
     */
    public static function get(string $class) {
        try {
            /**
             * @global Container $TLBM_DICONTAINER
             */
            global $TLBM_DICONTAINER;

            if($TLBM_DICONTAINER) {
                return $TLBM_DICONTAINER->get($class);
            }
        } catch (Throwable $exception) { }

        return null;
    }
}