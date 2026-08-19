<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Forms\Components;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;

class QrDesigner
{
    public static function make(string $name = 'qr_design'): Group
    {
        return Group::make([
            Select::make('dotsStyle')
                ->label(__('filament-short-url::resources/short-url.qr.dots_style'))
                ->helperText(__('filament-short-url::resources/short-url.qr.dots_style_helper'))
                ->options([
                    'square' => __('filament-short-url::resources/short-url.qr.style_square'),
                    'dots' => __('filament-short-url::resources/short-url.qr.style_dots'),
                    'rounded' => __('filament-short-url::resources/short-url.qr.style_rounded'),
                    'classy' => __('filament-short-url::resources/short-url.qr.style_classy'),
                    'extra-rounded' => __('filament-short-url::resources/short-url.qr.style_extra_rounded'),
                ])
                ->default('square')
                ->live(),

            Select::make('eyesStyle')
                ->label(__('filament-short-url::resources/short-url.qr.eyes_style'))
                ->helperText(__('filament-short-url::resources/short-url.qr.eyes_style_helper'))
                ->options([
                    'square' => __('filament-short-url::resources/short-url.qr.style_square'),
                    'dots' => __('filament-short-url::resources/short-url.qr.style_dots'),
                    'rounded' => __('filament-short-url::resources/short-url.qr.style_rounded'),
                ])
                ->default('square')
                ->live(),

            Select::make('errorCorrection')
                ->label(__('filament-short-url::resources/short-url.qr.error_correction'))
                ->options(['L' => 'L', 'M' => 'M', 'Q' => 'Q', 'H' => 'H'])
                ->default('M')
                ->live(),

            Slider::make('margin')
                ->label(__('filament-short-url::resources/short-url.qr.margin'))
                ->range(0, 50)
                ->default(0)
                ->live(),

            ColorPicker::make('gradient.from')
                ->label(__('filament-short-url::resources/short-url.qr.gradient_from'))
                ->live(),

            ColorPicker::make('gradient.to')
                ->label(__('filament-short-url::resources/short-url.qr.gradient_to'))
                ->live(),

            Select::make('gradient.type')
                ->label(__('filament-short-url::resources/short-url.qr.gradient_type'))
                ->options(['linear' => __('filament-short-url::resources/short-url.qr.gradient_linear'), 'radial' => __('filament-short-url::resources/short-url.qr.gradient_radial')])
                ->default('linear')
                ->live(),

            FileUpload::make('logoPath')
                ->label(__('filament-short-url::resources/short-url.qr.logo'))
                ->image()
                ->directory('short-url-qr-logos')
                ->live(),

            Slider::make('logoSizePercent')
                ->label(__('filament-short-url::resources/short-url.qr.logo_size'))
                ->range(5, 40)
                ->default(20)
                ->visible(fn (Get $get): bool => filled($get('logoPath')))
                ->live(),

            ViewField::make('preview')
                ->label(__('filament-short-url::resources/short-url.qr.preview'))
                ->dehydrated(false)
                ->view('filament-short-url::components.qr-preview', fn (Get $get): array => [
                    'design' => [
                        'dotsStyle' => $get('dotsStyle') ?? 'square',
                        'eyesStyle' => $get('eyesStyle') ?? 'square',
                        'errorCorrection' => $get('errorCorrection') ?? 'M',
                        'margin' => (int) ($get('margin') ?? 0),
                        'gradient' => filled($get('gradient.from')) && filled($get('gradient.to'))
                            ? ['from' => $get('gradient.from'), 'to' => $get('gradient.to'), 'type' => $get('gradient.type') ?? 'linear']
                            : null,
                        'logoPath' => $get('logoPath'),
                        'logoSizePercent' => (int) ($get('logoSizePercent') ?? 20),
                    ],
                ])
                ->columnSpanFull(),
        ])->columns(2)->statePath($name);
    }
}
