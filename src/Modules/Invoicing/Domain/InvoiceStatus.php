<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Domain;

enum InvoiceStatus: string
{
	case DRAFT = 'draft';
	case ISSUED = 'issued';
	case SENT = 'sent';
	case PAID = 'paid';
	case PARTIALLY = 'partially';
	case VOID = 'void';
}
