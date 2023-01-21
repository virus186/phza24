<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Events\OrderPlacedEvent;
use InvoiceHelper;
use Throwable;

class GenerateInvoiceListener
{
    /**
     * Handle the event.
     *
     * @param OrderPlacedEvent $event
     * @return void
     * @throws Throwable
     */
    public function handle(OrderPlacedEvent $event)
    {
        $order = $event->order;

        InvoiceHelper::store($order);
    }
}
