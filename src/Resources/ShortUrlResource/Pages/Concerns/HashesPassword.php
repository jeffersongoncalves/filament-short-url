<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns;

use Illuminate\Support\Facades\Hash;

trait HashesPassword
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function hashPassword(array $data): array
    {
        if (array_key_exists('password', $data)) {
            $data['password_hash'] = filled($data['password']) ? Hash::make($data['password']) : null;
            unset($data['password']);
        }

        return $data;
    }
}
