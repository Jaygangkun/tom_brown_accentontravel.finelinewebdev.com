<?php /* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:20:26 */

namespace XTP_BUILD\Illuminate\Contracts\Bus;

interface QueueingDispatcher extends Dispatcher
{
    /**
     * Dispatch a command to its appropriate handler behind a queue.
     *
     * @param  mixed  $command
     * @return mixed
     */
    public function dispatchToQueue($command);
}
