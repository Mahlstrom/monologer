<?php

use Bramus\Monolog\Formatter\ColoredLineFormatter;
use mahlstrom\monolog\MahlLight;
use mahlstrom\monolog\MemoryUsageFixedProcessor;
use mahlstrom\monolog\ShortLevel;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class LOG
 */
abstract class LOG
{

    /** @var LOG_timer[] */
    static private $timers = array();
    /** @var \Monolog\Logger[] */
    static private $logs = array();


    /**
     * @param $title
     * @param $numRows
     * @param \Monolog\Logger $logger
     * @return LOG_timer
     */
    static public function Timer($title, $numRows, \Monolog\Logger &$logger)
    {

        self::$timers[$title] = new LOG_timer($title, $numRows, false, $logger);

        return self::$timers[$title];
    }

    /**
     * @deprecated
     * @param $title
     */
    static public function TimerCheck($title)
    {

        static $called = array();
        self::getLogger();
        $caller = self::get_the_caller();
        if (!isset($called[$caller])) {
            self::$logs['_']->emerg('deprecated call TimerCheck: ' . $caller);
            $called[$caller] = true;
        }
        self::$timers[$title]->check();
    }

    /**
     * @param string $name
     * @return \Monolog\Logger
     */
    static public function &getLogger($name = '_')
    {

        self::initLogger($name);

        return self::$logs[$name];
    }

    /**
     * @param string $name
     */
    static private function initLogger($name = '_')
    {

        if (!isset(self::$logs[$name])) {
            if (class_exists('Config')) {
                if (isset(Config::$loglevels[$name])) {
                    $logLevel = Config::$loglevels[$name];
                } else {
                    $logLevel = Config::$loglevels['default'];
                }
            } else {
                $logLevel = \Monolog\Logger::DEBUG;
            }
            $formatString = "%datetime% [%extra.memory_usage%] %extra.short_level% [%channel%] %message%\n";

            $handler = new StreamHandler('php://stdout', $logLevel);
            $handler->setFormatter(new ColoredLineFormatter(new MahlLight(), $formatString));
            $log = new Logger('DEMO');
            $log->pushProcessor(new MemoryUsageFixedProcessor());
            $log->pushProcessor(new ShortLevel());
            $log->pushHandler($handler);

            self::$logs[$name] = $log;
        }
    }

    /**
     * @return string
     */
    private static function get_the_caller()
    {

        $trace = debug_backtrace();
        if (count($trace) > 2) {
            $caller = $trace[2];
            #print_r($caller);
            $ret = "{$caller['function']}" . '()';
            if (isset($caller['class'])) {
                $ret = "{$caller['class']}::" . $ret;
            }
            $ret .= ' in ' . $caller['file'];
            $ret .= ' on line ' . $caller['line'];

            return $ret;
        } else {
            $caller = $trace[1];
            $ret = 'in ' . $caller['file'];
            $ret .= ' on line ' . $caller['line'];

            return $ret;
        }
    }

    /**
     * @deprecated
     * @param $title
     */
    public static function TimerEnd($title)
    {

        $caller = self::get_the_caller();
        self::$logs['_']->emerg('deprecated call TimerEnd: ' . $caller);
        self::$timers[$title]->end();
    }

    /**
     * @param $string
     */
    static public function alert($string)
    {

        $caller = self::get_the_caller();
        if (preg_match('/^([^\ ]+):(.+)/', $string, $ar)) {
            $name = $ar[1];
            self::getLogger($name);
            self::$logs[$name]->alert($ar[2] . ' ' . $caller);
        } else {
            self::getLogger();
            self::$logs['_']->alert($string . ' (' . $caller . ')');
        }
    }
}
