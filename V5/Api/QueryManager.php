<?php
namespace HelloAsso\V5\Api;


use Exception;
use stdClass;

/**
 * API Response
 * 
 * @author fraoult
 * @license MIT
 */
class QueryManager
{

    /**
     * Maximum queries count in X seconds
     * @var int[]
     */
    public static $MaxQueriesPerSeconds = [25, 5]; // 25 queries in 5 seconds

    /**
     * Time of first query
     * @var int
     */
    protected static $time_start = 0;

    /**
     * Queries count
     * @var int
     */
    protected static  $n_queries = 0;

    public static function wait_for_query()
    {
        if (self::$time_start <= 0)
        {
            static::reset();
        }

        // Max query ? -> Waiting...

        $max_queries = static::$MaxQueriesPerSeconds[0]?:1;
        if (static::$n_queries >= $max_queries)
        {
            static::wait();
            static::reset();
        }

        return ++self::$n_queries;
    }


    protected static function wait()
    {
        $duration = (time() - static::$time_start) / 1000;
        $min_time = static::$MaxQueriesPerSeconds[1]?:1;
        $remaining_time = $min_time - $duration;

        if ($remaining_time > 0)
        {
            sleep((int)ceil($remaining_time));
            usleep(200000); // 0.2 second (safety margin)
        }
    }

    protected static function reset()
    {
        static::$time_start = time();
        self::$n_queries = 0;
    }
}
