# Filament Short URL

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/filament-short-url.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-short-url)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-short-url/tests.yml?branch=3.x&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/filament-short-url/actions?query=workflow%3Atests+branch%3A3.x)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-short-url/fix-php-code-style-issues.yml?branch=3.x&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/filament-short-url/actions?query=workflow%3A%22Fix+PHP+code+style+issues%22+branch%3A3.x)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/filament-short-url.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-short-url)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/filament-short-url.svg?style=flat-square)](LICENSE.md)

Um resource Filament para gerenciar encurtadores de URL usando o pacote
[`jeffersongoncalves/laravel-short-url`](https://github.com/jeffersongoncalves/laravel-short-url), que contém o
model, as migrations, o gerador de chaves e o pipeline de redirecionamento. Este pacote fornece **apenas a camada
de UI no Filament** (Resource com páginas de listagem, criação e edição).

## Compatibilidade

Este pacote é compatível apenas com **Filament v5** (branch `3.x`). Não há branches `1.x`/`2.x` porque o
`jeffersongoncalves/laravel-short-url` (dependência obrigatória) foi criado diretamente para Filament v5.

## Compatibilidade de versões

| `filament-short-url` | `laravel-short-url` | Filament |
| --- | --- | --- |
| `3.x` | `^1.0` | `v5` |

## Status: Fase F1

Esta é a fase fundacional. Ela entrega:

- `ShortUrlResource` com páginas de listagem, criação e edição.
- Colunas na listagem: chave curta (copiável), URL de destino (com tooltip), título, habilitado (toggle),
  total de visitas, expiração e data de criação.
- Formulário com validação de URL de destino, chave alfanumérica opcional (auto-gerada via `KeyGenerator` do
  pacote base quando deixada em branco), status de redirecionamento, limite de visitas, expiração, uso único e
  encaminhamento de parâmetros de query.
- Filtros de status (habilitado/desabilitado) e período de criação na listagem.
- `FilamentShortUrlPlugin` com navegação (grupo, label, ícone, ordem), Resources habilitáveis e opt-outs
  granulares (`hideStatistics()`, `hideBioPages()`) configuráveis.
- Autorização em três níveis: closure do plugin (`authorizeUsing()`) → `ShortUrlPolicy` do core → `canViewAny()`.
  A `ShortUrlPolicy` vive em `jeffersongoncalves/laravel-short-url` (o model é do core, a autorização do model
  também) e é registrada automaticamente via `Gate::policy()`.
- Traduções pt_BR, en e es.
- Arch test garantindo que o pacote nunca acessa tabelas do core via `DB::table()`/query builder cru.

Analytics, tipos de destino split/rules/geo-fence, QR codes, multi-tenancy, API pública e webhooks **não** fazem
parte da F1 e chegarão em fases futuras.

## Instalação

Você pode instalar o pacote via composer:

```bash
composer require jeffersongoncalves/filament-short-url:"^3.0"
```

Publique e rode as migrations do pacote base (`jeffersongoncalves/laravel-short-url`):

```bash
php artisan vendor:publish --tag="laravel-short-url-config"
php artisan vendor:publish --tag="laravel-short-url-migrations"
php artisan migrate
```

## Uso

Registre o plugin no seu `PanelProvider`:

```php
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentShortUrlPlugin::make()
                ->navigationGroup('Marketing')
                ->navigationLabel('Links Curtos')
                ->navigationIcon('heroicon-o-link')
                ->navigationSort(50)
                ->authorizeUsing(fn () => auth()->user()->hasRole('admin'))
                ->authorizeSettingsUsing(fn () => auth()->user()->hasRole('admin'))
                ->resources([
                    \JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource::class,
                ])
                ->hideStatistics()
                ->hideBioPages(),
        ]);
}
```

Isso registra o `ShortUrlResource` no painel, permitindo criar, listar e editar links curtos diretamente pela
interface administrativa. O redirecionamento em si (rota `GET /{urlKey}`) é responsabilidade do pacote base
`jeffersongoncalves/laravel-short-url` — consulte o README dele para configuração de rota, cache e chaves.

### Autorização

O acesso ao `ShortUrlResource` é resolvido em três níveis, na ordem:

1. **Closure do plugin** — `authorizeUsing()`. Se definida, decide sozinha (sobrepõe a Policy).
2. **`ShortUrlPolicy`** (registrada no core `laravel-short-url` via `Gate::policy()`), que autoriza tudo por
   padrão — sobrescreva-a no host app ou registre outra policy para o model `ShortUrl` para restringir.
3. **`canViewAny()`** do Resource, que consulta a Policy acima quando nenhuma closure foi definida.

`authorizeSettingsUsing()` segue o mesmo fallback e será consumido pela `SettingsPage` a partir da F4.

## Testes

```bash
composer test
```

## Changelog

Consulte o [CHANGELOG](CHANGELOG.md) para mais informações sobre o que mudou recentemente.

## Contributing

Consulte [CONTRIBUTING](.github/CONTRIBUTING.md) para detalhes.

## Security Vulnerabilities

Consulte [nossa política de segurança](../../security/policy) para saber como reportar vulnerabilidades.

## Créditos

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## Licença

MIT License (MIT). Veja o [arquivo de licença](LICENSE.md) para mais informações.
