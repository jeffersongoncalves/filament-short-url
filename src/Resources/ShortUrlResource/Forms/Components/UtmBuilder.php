<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Forms\Components;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use JeffersonGoncalves\LaravelShortUrl\Models\UtmTemplate;

/**
 * Keeps utm_source/medium/campaign/term/content in sync with the query
 * string on `destination_url`, in both directions: editing a UTM field
 * rewrites the destination URL's query string, and editing the destination
 * URL directly re-reads any utm_* params back into the fields.
 */
class UtmBuilder
{
    protected const FIELDS = ['source', 'medium', 'campaign', 'term', 'content'];

    public static function make(): Group
    {
        return Group::make([
            Select::make('utm_template_id')
                ->label(__('filament-short-url::resources/short-url.utm.template'))
                ->options(fn (): array => UtmTemplate::query()->pluck('name', 'id')->all())
                ->live()
                ->dehydrated(false)
                ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                    $template = $state ? UtmTemplate::query()->find($state) : null;

                    if (! $template) {
                        return;
                    }

                    foreach ($template->toUtmAttributes() as $key => $value) {
                        $set($key, $value);
                    }

                    static::syncToDestination($set, $get);
                }),

            ...collect(static::FIELDS)->map(fn (string $field): TextInput => TextInput::make("utm_{$field}")
                ->label(__("filament-short-url::resources/short-url.utm.{$field}"))
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, Get $get) => static::syncToDestination($set, $get)))->all(),
        ])->columns(3)->columnSpanFull();
    }

    protected static function syncToDestination(Set $set, Get $get): void
    {
        $url = $get('destination_url');

        if (! $url || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return;
        }

        $parts = parse_url($url);
        parse_str($parts['query'] ?? '', $query);

        foreach (static::FIELDS as $field) {
            $value = $get("utm_{$field}");

            if (filled($value)) {
                $query["utm_{$field}"] = $value;
            } else {
                unset($query["utm_{$field}"]);
            }
        }

        $rebuilt = ($parts['scheme'] ?? 'https').'://'.($parts['host'] ?? '')
            .($parts['path'] ?? '')
            .($query !== [] ? '?'.http_build_query($query) : '');

        $set('destination_url', $rebuilt);
    }
}
