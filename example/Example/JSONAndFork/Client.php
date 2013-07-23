#!/usr/bin/php
<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 7/22/13
 */

namespace Example\JSONAndFork;

use Net\Bazzline\Component\ProcessIdentity\Identity;

require_once __DIR__ . '/../../../vendor/autoload.php';

if ($argc != 5) {
    echo 'No valid arguments supplied' . PHP_EOL;
    exit(1);
}

$pid = $argv[1];
$numberOfLoops = (int) $argv[2];
$fails = (bool) $argv[3];
$failsCritical = (bool) $argv[4];

Client::create()
    ->setPid($pid)
    ->setNumberOfLoops($numberOfLoops)
    ->setFails($fails)
    ->setFailsCritical($failsCritical)
    ->printStatistic()
    ->andRun();

/**
 * Class Client
 *
 * @package Example\JSONAndFork
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-22
 */
class Client
{
    /**
     * @var int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected $currentLoop;

    /**
     * @var Heartbeat
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected $heartbeat;

    /**
     * @var int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected $numberOfLoops;

    /**
     * @var string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected $pid;

    /**
     * @return Client
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public static function create()
    {
        $self = new self();
        $self->pid = '';
        $self->currentLoop = 0;
        $self->numberOfLoops = 0;

        return $self;
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function __destruct()
    {
        $this->heartbeat->deleteFile();
    }

    /**
     * @param $pid
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
        $identity = new Identity();
        $identity->setId($this->pid);
        $this->heartbeat = new Heartbeat($identity);

        return $this;
    }

    /**
     * @param $numberOfLoops
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function setNumberOfLoops($numberOfLoops)
    {
        $this->numberOfLoops = (int) $numberOfLoops;

        return $this;
    }

    /**
     * @param $fails
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function setFails($fails)
    {
        if ($fails) {
            $this->heartbeat->setFailsOnBeatNumber(rand(1, ($this->numberOfLoops - 1)));
        }

        return $this;
    }

    /**
     * @param $failsCritical
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function setFailsCritical($failsCritical)
    {
        if ($failsCritical) {
            $this->heartbeat->setFailsOnBeatNumber(rand(1, ($this->numberOfLoops - 1)), true);
        }

        return $this;
    }

    /**
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function printStatistic()
    {
        echo PHP_EOL;
        echo str_repeat('-', 40) . PHP_EOL;
        echo 'pid: ' . $this->pid . PHP_EOL;
        echo 'heartbeat id: ' . $this->heartbeat->getIdentity()->getId() . PHP_EOL;
        echo 'Should fail: ' . ($this->heartbeat->shouldFail() ? 'yes' : 'no') . PHP_EOL;
        echo 'Should fail critical: ' . ($this->heartbeat->shouldFailCritical() ? 'yes' : 'no') . PHP_EOL;
        echo str_repeat('-', 40) . PHP_EOL;

        return $this;
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function andRun()
    {
        set_time_limit(2 * $this->numberOfLoops);
        $monitor = new Monitor();
        $monitor->attach($this->heartbeat);
        while ($this->currentLoop < $this->numberOfLoops) {
            $this->heartbeat->beat();
            sleep(1);
            $this->currentLoop++;
        }
        //$this->heartbeat->getIdentity()->getId();
        $monitor->detach($this->heartbeat);
    }
}