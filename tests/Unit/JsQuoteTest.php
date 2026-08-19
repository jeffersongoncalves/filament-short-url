<?php

use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Statistics;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Tables\ShortUrlsTable;

function callJsQuote(string $class, string $value): string
{
    $method = new ReflectionMethod($class, 'jsQuote');

    return $method->invoke(null, $value);
}

it('produces a single-quoted JS literal that survives Filament\'s " -> \" attribute escaping on Statistics', function () {
    $quoted = callJsQuote(Statistics::class, 'http://shorturlkit.test/fid6mku');

    expect($quoted)->toBe("'http://shorturlkit.test/fid6mku'");

    // Simulate Filament's own ComponentAttributeBag-style escaping (only " -> \").
    $attributeRendered = str_replace('"', '\\"', $quoted);

    expect($attributeRendered)->toBe($quoted);
});

it('produces a single-quoted JS literal that survives Filament\'s " -> \" attribute escaping on ShortUrlsTable', function () {
    $quoted = callJsQuote(ShortUrlsTable::class, 'http://shorturlkit.test/fid6mku');

    expect($quoted)->toBe("'http://shorturlkit.test/fid6mku'");

    $attributeRendered = str_replace('"', '\\"', $quoted);

    expect($attributeRendered)->toBe($quoted);
});

it('escapes embedded single quotes and backslashes on Statistics', function () {
    $quoted = callJsQuote(Statistics::class, "it's a \\test\\");

    expect($quoted)->toBe("'it\\'s a \\\\test\\\\'");
});

it('escapes embedded single quotes and backslashes on ShortUrlsTable', function () {
    $quoted = callJsQuote(ShortUrlsTable::class, "it's a \\test\\");

    expect($quoted)->toBe("'it\\'s a \\\\test\\\\'");
});
