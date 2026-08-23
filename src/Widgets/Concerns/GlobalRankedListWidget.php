<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Widgets\Concerns;

use Filament\Widgets\Widget;

abstract class GlobalRankedListWidget extends Widget
{
    use HasGlobalStatsPayload;

    /**
     * @var view-string
     */
    protected static string $view = 'filament-short-url::widgets.ranked-list';

    protected int|string|array $columnSpan = 1;

    abstract protected function statsKey(): string;

    abstract protected function heading(): string;

    protected function isCountryCode(): bool
    {
        return false;
    }

    protected function getViewData(): array
    {
        $stats = $this->getGlobalPayload()->{$this->statsKey()};

        arsort($stats);
        $stats = array_slice($stats, 0, 8, true);
        $max = $stats === [] ? 0 : max($stats);

        $flags = $this->isCountryCode()
            ? array_combine(array_keys($stats), array_map($this->flagEmoji(...), array_keys($stats)))
            : [];

        return [
            'heading' => $this->heading(),
            'stats' => $stats,
            'max' => $max,
            'flags' => $flags,
        ];
    }

    private function flagEmoji(string $countryCode): string
    {
        $countryCode = strtoupper($countryCode);

        if (strlen($countryCode) !== 2) {
            return '';
        }

        return mb_chr(127397 + ord($countryCode[0])).mb_chr(127397 + ord($countryCode[1]));
    }
}
