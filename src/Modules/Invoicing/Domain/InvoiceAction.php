<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Domain;

enum InvoiceAction: string
{
	case SaveDraft = 'save_draft';
	case SaveChanges = 'save_changes';
	case Issue = 'issue';

	case Void = 'void';

	public static function actionStatus(string $action) : string
	{
		return match($action) {
			'save_draft' => 'created',
			'save_changes' => 'saved',
			'issue' => 'issued',
		};
	}
}
