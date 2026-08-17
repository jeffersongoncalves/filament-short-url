<?php

namespace JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

class ShortUrlForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('destination_url')
                ->label(__('filament-short-url::resources/short-url.fields.destination_url'))
                ->required()
                ->url()
                ->maxLength(65535)
                ->columnSpanFull(),

            TextInput::make('url_key')
                ->label(__('filament-short-url::resources/short-url.fields.url_key'))
                ->helperText(__('filament-short-url::resources/short-url.fields.url_key_helper'))
                ->nullable()
                ->alphaDash()
                ->maxLength(64)
                ->unique(
                    table: (new ShortUrl)->getTable(),
                    column: 'url_key',
                    ignoreRecord: true,
                    modifyRuleUsing: fn (Unique $rule): Unique => $rule->whereNull('custom_domain_id'),
                ),

            TextInput::make('title')
                ->label(__('filament-short-url::resources/short-url.fields.title'))
                ->maxLength(255),

            Textarea::make('notes')
                ->label(__('filament-short-url::resources/short-url.fields.notes'))
                ->rows(3)
                ->columnSpanFull(),

            Toggle::make('is_enabled')
                ->label(__('filament-short-url::resources/short-url.fields.is_enabled'))
                ->default(true),

            Toggle::make('single_use')
                ->label(__('filament-short-url::resources/short-url.fields.single_use'))
                ->default(false),

            Toggle::make('forward_query_params')
                ->label(__('filament-short-url::resources/short-url.fields.forward_query_params'))
                ->default(true),

            Select::make('redirect_status_code')
                ->label(__('filament-short-url::resources/short-url.fields.redirect_status_code'))
                ->options([
                    301 => '301',
                    302 => '302',
                    307 => '307',
                    308 => '308',
                ])
                ->default(302)
                ->required(),

            TextInput::make('max_visits')
                ->label(__('filament-short-url::resources/short-url.fields.max_visits'))
                ->numeric()
                ->nullable(),

            DateTimePicker::make('expires_at')
                ->label(__('filament-short-url::resources/short-url.fields.expires_at'))
                ->nullable(),
        ]);
    }
}
