<?php

namespace TLBM;

use DI\Container;
use Exception;

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
        } catch (Exception $exception) {
            if(WP_DEBUG) {
                var_dump($exception->getMessage());
            }
        }

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
        } catch (Exception $exception) {
            if(WP_DEBUG) {
                var_dump($exception->getMessage());
            }
        }

        return null;
    }
}