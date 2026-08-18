<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Forms\Components\RuleBuilder;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Forms\Components\SplitSlider;
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

            Select::make('destination_type')
                ->label(__('filament-short-url::resources/short-url.fields.destination_type'))
                ->options([
                    'single' => __('filament-short-url::resources/short-url.fields.destination_type_single'),
                    'rules' => __('filament-short-url::resources/short-url.fields.destination_type_rules'),
                    'split' => __('filament-short-url::resources/short-url.fields.destination_type_split'),
                ])
                ->default('single')
                ->live()
                ->required()
                ->columnSpanFull(),

            RuleBuilder::make('targeting_rules')
                ->visible(fn (Get $get): bool => $get('destination_type') === 'rules')
                ->columnSpanFull(),

            SplitSlider::make('rotation_variants')
                ->visible(fn (Get $get): bool => $get('destination_type') === 'split')
                ->columnSpanFull(),

            Section::make(__('filament-short-url::resources/short-url.security.section'))
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('password')
                        ->label(__('filament-short-url::resources/short-url.security.password'))
                        ->password()
                        ->revealable()
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->helperText(__('filament-short-url::resources/short-url.security.password_helper')),

                    TextInput::make('password_hint')
                        ->label(__('filament-short-url::resources/short-url.security.password_hint'))
                        ->maxLength(255),

                    Toggle::make('show_warning_page')
                        ->label(__('filament-short-url::resources/short-url.security.show_warning_page'))
                        ->live(),

                    Textarea::make('warning_message')
                        ->label(__('filament-short-url::resources/short-url.security.warning_message'))
                        ->rows(2)
                        ->visible(fn (Get $get): bool => (bool) $get('show_warning_page')),

                    Select::make('safe_browsing_status')
                        ->label(__('filament-short-url::resources/short-url.security.safe_browsing_status'))
                        ->options([
                            'safe' => __('filament-short-url::resources/short-url.security.safe_browsing_safe'),
                            'unsafe' => __('filament-short-url::resources/short-url.security.safe_browsing_unsafe'),
                            'unknown' => __('filament-short-url::resources/short-url.security.safe_browsing_unknown'),
                        ])
                        ->disabled()
                        ->dehydrated(false)
                        ->visible(fn (?ShortUrl $record): bool => $record?->exists && $record->safe_browsing_status !== null),
                ]),
        ]);
    }
}
