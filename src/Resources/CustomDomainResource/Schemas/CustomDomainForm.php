<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

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

            TextInput::make('root_redirect_url')
                ->label(__('filament-short-url::resources/custom-domain.fields.root_redirect_url'))
                ->url()
                ->nullable()
                ->columnSpanFull(),
        ]);
    }
}
