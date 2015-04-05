<?php
/**
 * Created by PhpStorm.
 * User: mahlstrom
 * Date: 26/04/14
 * Time: 19:55
 */

class LOG_timer {

    /**
     * @var string
     */
    private $title = '';
    /**
     * @var float|mixed
     */
    private $init = 0.0;
    /**
     * @var int
     */
    private $NR = 0;
    /**
     * @var float
     */
    private $Obj = 0.0;
    /**
     * @var int
     */
    private $Row = 0;
    /**
     * @var bool
     */
    private $quick = false;
    /**
     * @var float
     */
    private $PROGRESS;
    /**
     * @var int
     */
    private $LAST = 0;

    /**
     * @param $title
     * @param $numRows
     * @param $quick
     */
    /**
     * @var \Monolog\Logger
     */
    private $log;

    public function __construct($title, $numRows, $quick, $logger) {
        $this->log = $logger;
        $this->title = $title;
        $this->init = $this->Obj = microtime(true);
        $this->NR = $numRows;
        $this->Row = 0;
        $this->quick = $quick;
        $this->PROGRESS = floor($numRows / 100);
        $this->LAST = 0;
        $this->writeWeAre($title, 0, 0, $this->NR, 0);
    }

    /**
     *
     */
    public function check() {

        $this->Row++;
        if($this->Obj < microtime(true) - 3) {
            $this->Obj = microtime(true);
            $percentage = round(($this->Row / $this->NR) * 100);
            self::writeWeAre($this->title, $percentage, $this->Row, $this->NR, $this->Row - $this->LAST);
            $this->LAST = $this->Row;
        }
    }

    /**
     *
     */
    public function end() {

        $rounded = round(microtime(true) - $this->init, 2);
        $this->log->notice("Timer:[$this->title] We are on 100% Operation-time:" . $rounded);
    }

    /**
     * @param $title
     * @param $percentage
     * @param $row
     * @param $total
     * @param $linesDone
     */
    private function writeWeAre($title, $percentage, $row, $total, $linesDone) {

        $nrof = strlen($total);
        $this->log->notice(sprintf('Timer:[%s] We are on %3d%%  ( %' . $nrof . 'd / %d) %' . $nrof . 'd lines done', $title, $percentage, $row, $total, $linesDone));
    }
}

