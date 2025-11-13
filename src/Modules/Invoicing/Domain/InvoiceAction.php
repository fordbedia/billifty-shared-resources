<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Domain;

enum InvoiceAction: string
{
	case SaveDraft = 'save_draft';
	case SaveChanges = 'save_changes';
	case Issue = 'issue';
}
