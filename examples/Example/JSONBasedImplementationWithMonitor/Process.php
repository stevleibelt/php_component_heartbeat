<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-07-17
 */

namespace Example\JSONBasedImplementationWithMonitor;

use Net\Bazzline\Component\Heartbeat\ClientAwareInterface;
use Net\Bazzline\Component\Heartbeat\ClientInterface;

/**
 * Class Process
 *
 * @package Example\JSONBasedImplementation
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-07-17
 */
class Process implements ClientAwareInterface
{
    /**
     * @var Heartbeat
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-18
     */
    protected $heartbeat;

    /**
     * {@inheritdoc}
     */
    public function getHeartbeatClient()
    {
        return $this->heartbeat;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeartbeatClient()
    {
        return (!is_null($this->heartbeat));
    }

    /**
     * {@inheritdoc}
     */
    public function setHeartbeatClient(ClientInterface $heartbeat)
    {
        $this->heartbeat = $heartbeat;

        return $this;
    }

    /**
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-18
     */
    public function execute()
    {
        //that is the area where you should implement your logic by iterating
        // over a collection
        //--logic start
        $this->heartbeat->beat();
        //--logic end
    }
}