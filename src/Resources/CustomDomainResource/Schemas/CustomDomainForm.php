<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use JeffersonGoncalves\LaravelShortUrl\Models\CustomDomain;

class CustomDomainForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('domain')
                ->label(__('filament-short-url::resources/custom-domain.fields.domain'))
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->columnSpanFull(),

            Toggle::make('is_wildcard')
                ->label(__('filament-short-url::resources/custom-domain.fields.is_wildcard'))
                ->helperText(__('filament-short-url::resources/custom-domain.fields.is_wildcard_helper')),

            Toggle::make('is_default')
                ->label(__('filament-short-url::resources/custom-domain.fields.is_default'))
                ->helperText(__('filament-short-url::resources/custom-domain.fields.is_default_helper'))
                // Marking an unverified domain default would silently break every new
                // link's URL — CustomDomain::default() only ever considers active()
                // (verified, enabled) domains, so an unverified default is a no-op
                // that misleads whoever toggled it into thinking it took effect.
                ->disabled(fn (?CustomDomain $record): bool => $record === null || ! $record->is_verified),

            TextInput::make('root_redirect_url')
                ->label(__('filament-short-url::resources/custom-domain.fields.root_redirect_url'))
                ->url()
                ->nullable()
                ->columnSpanFull(),
        ]);
    }
}
