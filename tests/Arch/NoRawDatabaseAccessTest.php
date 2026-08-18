<?php

arch('does not use the DB facade or raw query builder directly')
    ->expect('JeffersonGoncalves\Filament\ShortUrl')
    ->not->toUse([
        'Illuminate\Support\Facades\DB',
        'Illuminate\Database\Query\Builder',
        'Illuminate\Database\Connection',
    ]);
