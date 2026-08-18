<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets;

use Filament\Widgets\Widget;
use JeffersonGoncalves\LaravelShortUrl\Models\AuditLog;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

class AuditTimeline extends Widget
{
    public ?ShortUrl $record = null;

    /**
     * @var view-string
     */
    protected string $view = 'filament-short-url::widgets.audit-timeline';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'logs' => $this->record
                ? AuditLog::query()
                    ->where('short_url_id', $this->record->id)
                    ->latest('id')
                    ->limit(20)
                    ->get()
                : collect(),
        ];
    }
}
