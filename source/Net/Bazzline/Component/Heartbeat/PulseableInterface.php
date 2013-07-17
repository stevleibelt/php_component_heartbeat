<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14 
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class PulseableInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
interface PulseableInterface
{
    /**
     * @return integer - tells how often (in seconds) the knock should be called
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function getPulse();

    /**
     * @param integer $seconds - sets how often (in seconds) the knock should be called)
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function setPulse($seconds);
}