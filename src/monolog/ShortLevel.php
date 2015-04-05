<?php namespace mahlstrom\monolog;
/**
 * Created by PhpStorm.
 * User: mahlstrom
 * Date: 05/04/15
 * Time: 13:03
 */

class ShortLevel {
    public function __invoke(array $record)
    {
        if($record['level_name']=='EMERGENCY'){
            $lvl='#';
        }else{
            $lvl=substr($record['level_name'],0,1);
        }

        $record['extra'] = array_merge(
            $record['extra'],
            array(
                'short_level' => $lvl,
            )
        );

        return $record;
    }
}