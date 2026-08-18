<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WebhookForm
{
    /**
     * @var array<string, string>
     */
    public const EVENTS = [
        '*' => 'All events',
        'link.created' => 'link.created',
        'link.updated' => 'link.updated',
        'link.deleted' => 'link.deleted',
        'link.visited' => 'link.visited',
        'link.unsafe_detected' => 'link.unsafe_detected',
        'domain.verified' => 'domain.verified',
        'domain.failed' => 'domain.failed',
        'alert.triggered' => 'alert.triggered',
        'conversion.recorded' => 'conversion.recorded',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('url')
                ->label(__('filament-short-url::resources/webhook.fields.url'))
                ->required()
                ->url()
                ->columnSpanFull(),

            CheckboxList::make('events')
                ->label(__('filament-short-url::resources/webhook.fields.events'))
                ->options(static::EVENTS)
                ->required()
                ->columns(2)
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label(__('filament-short-url::resources/webhook.fields.is_active'))
                ->default(true),
        ]);
    }
}
