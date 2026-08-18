<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Forms\Components;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use JeffersonGoncalves\LaravelShortUrl\Registries\FilterTypeRegistry;

/**
 * Repeater for `targeting_rules`: top-down conditional rules, first match
 * wins. Condition types come from the core's FilterTypeRegistry — registering
 * a new filter type there makes it appear here without touching this class.
 */
class RuleBuilder
{
    public static function make(string $name = 'targeting_rules'): Repeater
    {
        return Repeater::make($name)
            ->label(__('filament-short-url::resources/short-url.rules.targeting_rules'))
            ->addActionLabel(__('filament-short-url::resources/short-url.rules.add_rule'))
            ->reorderable()
            ->collapsible()
            ->itemLabel(fn (array $state): string => $state['destination'] ?? __('filament-short-url::resources/short-url.rules.rule'))
            ->schema([
                Select::make('match')
                    ->label(__('filament-short-url::resources/short-url.rules.match'))
                    ->options([
                        'and' => __('filament-short-url::resources/short-url.rules.match_all'),
                        'or' => __('filament-short-url::resources/short-url.rules.match_any'),
                    ])
                    ->default('and')
                    ->required(),

                static::conditions(),

                Toggle::make('use_split')
                    ->label(__('filament-short-url::resources/short-url.rules.use_split'))
                    ->live()
                    ->dehydrated(false)
                    ->afterStateHydrated(fn (Toggle $component, Get $get): mixed => $component->state(filled($get('split')))),

                TextInput::make('destination')
                    ->label(__('filament-short-url::resources/short-url.rules.destination'))
                    ->url()
                    ->required()
                    ->visible(fn (Get $get): bool => ! $get('use_split')),

                SplitSlider::make('split')
                    ->visible(fn (Get $get): bool => (bool) $get('use_split')),
            ])
            ->columns(1);
    }

    protected static function conditions(): Repeater
    {
        return Repeater::make('conditions')
            ->label(__('filament-short-url::resources/short-url.rules.conditions'))
            ->addActionLabel(__('filament-short-url::resources/short-url.rules.add_condition'))
            ->schema([
                Select::make('type')
                    ->label(__('filament-short-url::resources/short-url.rules.condition_type'))
                    ->options(fn (): array => collect(app(FilterTypeRegistry::class)->all())
                        ->mapWithKeys(fn ($filterType, $key) => [$key => $filterType->label])
                        ->all())
                    ->live()
                    ->required(),

                ...static::conditionValueFields(),
            ])
            ->columns(2);
    }

    /**
     * @return array<int, Component>
     */
    protected static function conditionValueFields(): array
    {
        $enumTypes = ['device', 'platform', 'browser'];
        $textEnumTypes = ['country', 'region', 'city'];

        return [
            Select::make('operator')
                ->label(__('filament-short-url::resources/short-url.rules.operator'))
                ->options([
                    'in' => __('filament-short-url::resources/short-url.rules.operator_in'),
                    'not_in' => __('filament-short-url::resources/short-url.rules.operator_not_in'),
                ])
                ->default('in')
                ->visible(fn (Get $get): bool => in_array($get('type'), [...$enumTypes, ...$textEnumTypes], true)),

            Select::make('value')
                ->label(__('filament-short-url::resources/short-url.rules.value'))
                ->options(fn (Get $get): array => collect(static::filterOptions($get('type')))
                    ->mapWithKeys(fn (array $option): array => [$option['value'] => $option['label']])
                    ->all())
                ->multiple()
                ->required()
                ->visible(fn (Get $get): bool => in_array($get('type'), $enumTypes, true)),

            TextInput::make('value')
                ->label(__('filament-short-url::resources/short-url.rules.value'))
                ->required()
                ->visible(fn (Get $get): bool => in_array($get('type'), $textEnumTypes, true)
                    || $get('type') === 'language'),

            TextInput::make('value')
                ->label(__('filament-short-url::resources/short-url.rules.value'))
                ->numeric()
                ->required()
                ->visible(fn (Get $get): bool => $get('type') === 'visit_count'),

            Select::make('operator')
                ->label(__('filament-short-url::resources/short-url.rules.operator'))
                ->options([
                    'gt' => __('filament-short-url::resources/short-url.rules.operator_gt'),
                    'gte' => __('filament-short-url::resources/short-url.rules.operator_gte'),
                    'lt' => __('filament-short-url::resources/short-url.rules.operator_lt'),
                    'lte' => __('filament-short-url::resources/short-url.rules.operator_lte'),
                    'eq' => __('filament-short-url::resources/short-url.rules.operator_eq'),
                ])
                ->default('gte')
                ->visible(fn (Get $get): bool => $get('type') === 'visit_count'),

            Select::make('operator')
                ->label(__('filament-short-url::resources/short-url.rules.operator'))
                ->options([
                    'contains' => __('filament-short-url::resources/short-url.rules.operator_contains'),
                    'equals' => __('filament-short-url::resources/short-url.rules.operator_equals'),
                    'type' => __('filament-short-url::resources/short-url.rules.operator_referer_type'),
                ])
                ->default('contains')
                ->visible(fn (Get $get): bool => $get('type') === 'referer'),

            TextInput::make('value')
                ->label(__('filament-short-url::resources/short-url.rules.value'))
                ->required()
                ->visible(fn (Get $get): bool => $get('type') === 'referer'),

            Select::make('value')
                ->label(__('filament-short-url::resources/short-url.rules.value'))
                ->options([
                    '1' => __('filament-short-url::resources/short-url.rules.yes'),
                    '0' => __('filament-short-url::resources/short-url.rules.no'),
                ])
                ->visible(fn (Get $get): bool => static::filterFieldType($get('type')) === 'boolean'),

            Select::make('field')
                ->label(__('filament-short-url::resources/short-url.rules.utm_field'))
                ->options([
                    'source' => 'utm_source',
                    'medium' => 'utm_medium',
                    'campaign' => 'utm_campaign',
                    'term' => 'utm_term',
                    'content' => 'utm_content',
                ])
                ->default('source')
                ->visible(fn (Get $get): bool => $get('type') === 'utm'),

            Select::make('operator')
                ->label(__('filament-short-url::resources/short-url.rules.operator'))
                ->options([
                    'equals' => __('filament-short-url::resources/short-url.rules.operator_equals'),
                    'contains' => __('filament-short-url::resources/short-url.rules.operator_contains'),
                ])
                ->default('equals')
                ->visible(fn (Get $get): bool => $get('type') === 'utm'),

            TextInput::make('value')
                ->label(__('filament-short-url::resources/short-url.rules.value'))
                ->required()
                ->visible(fn (Get $get): bool => $get('type') === 'utm'),

            TextInput::make('param')
                ->label(__('filament-short-url::resources/short-url.rules.query_param'))
                ->visible(fn (Get $get): bool => $get('type') === 'query_param'),

            Select::make('operator')
                ->label(__('filament-short-url::resources/short-url.rules.operator'))
                ->options([
                    'exists' => __('filament-short-url::resources/short-url.rules.operator_exists'),
                    'not_exists' => __('filament-short-url::resources/short-url.rules.operator_not_exists'),
                    'equals' => __('filament-short-url::resources/short-url.rules.operator_equals'),
                    'contains' => __('filament-short-url::resources/short-url.rules.operator_contains'),
                ])
                ->default('exists')
                ->visible(fn (Get $get): bool => $get('type') === 'query_param'),

            TextInput::make('value')
                ->label(__('filament-short-url::resources/short-url.rules.value'))
                ->visible(fn (Get $get): bool => $get('type') === 'query_param'),

            CheckboxList::make('days')
                ->label(__('filament-short-url::resources/short-url.rules.days'))
                ->options([
                    1 => __('filament-short-url::resources/short-url.rules.monday'),
                    2 => __('filament-short-url::resources/short-url.rules.tuesday'),
                    3 => __('filament-short-url::resources/short-url.rules.wednesday'),
                    4 => __('filament-short-url::resources/short-url.rules.thursday'),
                    5 => __('filament-short-url::resources/short-url.rules.friday'),
                    6 => __('filament-short-url::resources/short-url.rules.saturday'),
                    7 => __('filament-short-url::resources/short-url.rules.sunday'),
                ])
                ->columns(4)
                ->visible(fn (Get $get): bool => $get('type') === 'datetime'),

            TimePicker::make('from')
                ->label(__('filament-short-url::resources/short-url.rules.from_time'))
                ->visible(fn (Get $get): bool => $get('type') === 'datetime'),

            TimePicker::make('to')
                ->label(__('filament-short-url::resources/short-url.rules.to_time'))
                ->visible(fn (Get $get): bool => $get('type') === 'datetime'),

            TextInput::make('timezone')
                ->label(__('filament-short-url::resources/short-url.rules.timezone'))
                ->default(config('app.timezone'))
                ->visible(fn (Get $get): bool => $get('type') === 'datetime'),
        ];
    }

    protected static function filterFieldType(?string $type): ?string
    {
        if (! $type) {
            return null;
        }

        return app(FilterTypeRegistry::class)->get($type)?->fieldType;
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    protected static function filterOptions(?string $type): array
    {
        if (! $type) {
            return [];
        }

        $filterType = app(FilterTypeRegistry::class)->get($type);

        return $filterType === null ? [] : $filterType->options;
    }
}
