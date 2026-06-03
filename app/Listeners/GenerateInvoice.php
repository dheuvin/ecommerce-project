<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Support\InvoiceGenerator;

class GenerateInvoice
{
    public function __construct(
        private readonly InvoiceGenerator $invoiceGenerator
    )
    {
    }

    public function handle(OrderPlaced $event): void
    {
        $this->invoiceGenerator->generate($event->order);
    }
}
