<?php

namespace App\Enums;

enum InvoiceStatus: string {
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Partial = 'partial';
    case Cancelled = 'cancelled';
}
