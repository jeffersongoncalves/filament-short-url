<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Forms\Components;

use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Actions as ActionsComponent;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

/**
 * A/B/n weighted rotation editor for `rotation_variants` (or a nested
 * `split` object inside a targeting rule). Weights are plain number inputs
 * summing to 100 — no draggable handles; the "Balance" action redistributes
 * evenly on demand.
 */
class SplitSlider
{
    public static function make(string $name = 'rotation_variants'): Group
    {
        return Group::make([
            Toggle::make('sticky')
                ->label(__('filament-short-url::resources/short-url.split.sticky'))
                ->helperText(__('filament-short-url::resources/short-url.split.sticky_helper')),

            ActionsComponent::make([
                Action::make('balance')
                    ->label(__('filament-short-url::resources/short-url.split.balance'))
                    ->color('gray')
                    ->action(function (Get $get, Set $set): void {
                        $variants = $get('variants') ?? [];
                        $count = count($variants);

                        if ($count === 0) {
                            return;
                        }

                        $base = intdiv(100, $count);
                        $remainder = 100 - ($base * $count);

                        foreach (array_keys($variants) as $index) {
                            $variants[$index]['weight'] = $base + ($index === array_key_first($variants) ? $remainder : 0);
                        }

                        $set('variants', $variants);
                    }),
            ]),

            Repeater::make('variants')
                ->label(__('filament-short-url::resources/short-url.split.variants'))
                ->addActionLabel(__('filament-short-url::resources/short-url.split.add_variant'))
                ->schema([
                    TextInput::make('label')
                        ->label(__('filament-short-url::resources/short-url.split.variant_label')),
                    TextInput::make('url')
                        ->label(__('filament-short-url::resources/short-url.split.variant_url'))
                        ->url()
                        ->required(),
                    TextInput::make('weight')
                        ->label(__('filament-short-url::resources/short-url.split.variant_weight'))
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->suffix('%')
                        ->required(),
                ])
                ->columns(3)
                ->minItems(2)
                ->rule(fn (): Closure => static::sumsTo100())
                ->reorderable(),
        ])->statePath($name);
    }

    protected static function sumsTo100(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            $total = collect($value)->sum(fn (array $variant): int => (int) ($variant['weight'] ?? 0));

            if ($total !== 100) {
                $fail(__('filament-short-url::resources/short-url.split.weights_must_sum_to_100', ['total' => $total]));
            }
        };
    }
}
