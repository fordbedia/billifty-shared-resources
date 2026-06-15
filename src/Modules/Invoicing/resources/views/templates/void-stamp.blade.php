@if(($statusRaw ?? null) === 'void')
	<div class="invoice-void-stamp{{ ($renderContext ?? null) === 'pdf' ? ' is-pdf' : '' }}" aria-label="Void">VOID</div>
@endif
