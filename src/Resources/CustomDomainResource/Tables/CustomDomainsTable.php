<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use JeffersonGoncalves\LaravelShortUrl\Jobs\VerifyDomainJob;
use JeffersonGoncalves\LaravelShortUrl\Models\CustomDomain;

class CustomDomainsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('domain')
                    ->label(__('filament-short-url::resources/custom-domain.fields.domain'))
                    ->searchable()
                    ->copyable(),

                IconColumn::make('is_verified')
                    ->label(__('filament-short-url::resources/custom-domain.fields.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('success')
                    ->falseColor(fn (CustomDomain $record): string => $record->disabled_at ? 'danger' : 'warning'),

                TextColumn::make('dns_record_type')
                    ->label(__('filament-short-url::resources/custom-domain.fields.dns_record_type'))
                    ->badge(),

                IconColumn::make('is_wildcard')
                    ->label(__('filament-short-url::resources/custom-domain.fields.is_wildcard'))
                    ->boolean(),

                TextColumn::make('last_checked_at')
                    ->label(__('filament-short-url::resources/custom-domain.fields.last_checked_at'))
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('dns_instructions')
                    ->label(__('filament-short-url::resources/custom-domain.actions.dns_instructions'))
                    ->icon('heroicon-o-information-circle')
                    ->color('gray')
                    ->modalHeading(__('filament-short-url::resources/custom-domain.actions.dns_instructions'))
                    ->modalContent(fn (CustomDomain $record) => view('filament-short-url::components.dns-instructions', [
                        'domain' => $record,
                        'txtHost' => '_short-url-verify.'.$record->domain,
                        'txtValue' => $record->verification_token,
                        'cnameTarget' => config('short-url.route.domain') ?? parse_url((string) config('app.url'), PHP_URL_HOST),
                        'registrars' => [
                            'cloudflare' => 'Cloudflare',
                            'godaddy' => 'GoDaddy',
                            'registrobr' => 'Registro.br',
                            'namecheap' => 'Namecheap',
                            'hostinger' => 'Hostinger',
                        ],
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('filament-short-url::resources/custom-domain.actions.close')),

                Action::make('verify')
                    ->label(__('filament-short-url::resources/custom-domain.actions.verify_now'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->action(function (CustomDomain $record): void {
                        VerifyDomainJob::dispatch($record->id);

                        Notification::make()
                            ->title(__('filament-short-url::resources/custom-domain.actions.verify_queued'))
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
