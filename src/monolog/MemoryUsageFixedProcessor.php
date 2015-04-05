<?php namespace mahlstrom\monolog;
use Monolog\Processor\MemoryUsageProcessor;

/**
 * Created by PhpStorm.
 * User: mahlstrom
 * Date: 05/04/15
 * Time: 13:11
 */
class MemoryUsageFixedProcessor extends MemoryUsageProcessor
{

    public function __invoke(array $record)
    {
        $record = parent::__invoke($record);
        $record['extra']['memory_usage']=sprintf('%7s',$record['extra']['memory_usage']);
        return $record;
    }
}
