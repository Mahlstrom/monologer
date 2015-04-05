<?php
/**
 * Created by PhpStorm.
 * User: mahlstrom
 * Date: 23/04/14
 * Time: 11:38
 *
 * Returns time since execution in float
 */
namespace mahlstrom\monolog;

class monologTimeSinceExecProcessor {
	/**
	 * @param  array $record
	 * @return array
	 */
	public function __invoke(array $record) {
		$record['extra'] = array_merge(
			$record['extra'],
			array(
				'time_since_exec' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
			)
		);
		return $record;
	}
}
