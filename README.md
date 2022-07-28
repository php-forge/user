<p align="center">
    <a href="https://github.com/php-forge/user" target="_blank">
        <img src="https://avatars.githubusercontent.com/u/103309199?s=400&u=ca3561c692f53ed7eb290d3bb226a2828741606f&v=4" height="100px">
    </a>
    <h1 align="center">Modulo flexible de registro de usuario y autenticación para Yii3</h1>
    <br>
</p>

[![codeception](https://github.com/php-forge/user/actions/workflows/codeception.yml/badge.svg)](https://github.com/php-forge/user/actions/workflows/codeception.yml)
[![codecov](https://codecov.io/gh/php-forge/user/branch/main/graph/badge.svg?token=KB6T5KMGED)](https://codecov.io/gh/php-forge/user)
[![static analysis](https://github.com/php-forge/user/workflows/static%20analysis/badge.svg)](https://github.com/php-forge/user/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/php-forge/user/coverage.svg)](https://shepherd.dev/github/php-forge/user)

## Instalación

```shell
composer require forge/user
```
## Como usar el módulo

### Aplicando migraciones

```shell
./yii migrate/up --no-interaction
```

### Uso del servidor php incorporado

```shell
php -S 127.0.0.1:8080 -t public
```

### Espere hasta que esté listo, luego abra la siguiente URL en su navegador

```shell
http://localhost:8080
```

### Rutas implementadas

```shell
[/login] - Mostrar formulario de inicio de sesión.
[/logout] - Cierra la sesión del usuario.
[/confirm[/{id}/{token}]] - Confirma un usuario (requiere parámetros de consulta de id y token).
[/profile] - Muestra el formulario de perfil.
[/register] - Muestra el formulario de inscripción.
[/request] - Muestra el formulario de solicitud de recuperación.
[/resend] - Muestra el formulario de reenvío.
[/reset[/{id}/{token}]] - Muestra el formulario de restablecimiento de contraseña (requiere parámetros de consulta de id y token).
[/email/change] - Muestra el formulario de cambio de correo electrónico.
[/email/attempt[/{id}/{token}]] - Confirme el cambio de correo electrónico (requiere parámetros de consulta de id y token).
```

### Configuración

Agregue la configuración de autenticación de usuario: config/web/auth.php

```shell
<?php

declare(strict_types=1);

use Forge\User\Repository\IdentityRepository;
use Psr\Log\LoggerInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Cookies\CookieEncryptor;
use Yiisoft\Cookies\CookieMiddleware;
use Yiisoft\Cookies\CookieSigner;
use Yiisoft\Definitions\Reference;
use Yiisoft\Session\SessionInterface;
use Yiisoft\User\CurrentUser;
use Yiisoft\User\Login\Cookie\CookieLogin;

/** @var array $params */

return [
    IdentityRepositoryInterface::class => IdentityRepository::class,

    CookieMiddleware::class => static fn (CookieLogin $cookieLogin, LoggerInterface $logger) => new CookieMiddleware(
        $logger,
        new CookieEncryptor($params['yiisoft/cookies']['secretKey']),
        new CookieSigner($params['yiisoft/cookies']['secretKey']),
        [$cookieLogin->getCookieName() => CookieMiddleware::SIGN],
    ),

    CurrentUser::class => [
        'withSession()' => [Reference::to(SessionInterface::class)],
        'reset' => function () {
            $this->clear();
        },
    ],
];
```

Agregue la configuración de conexión de base de datos: config/common/db.php

```shell
<?php

declare(strict_types=1);

use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Sqlite\ConnectionPDO;
use Yiisoft\Db\Sqlite\PDODriver;

/** @var array $params */

return [
    ConnectionInterface::class => [
        'class' => ConnectionPDO::class,
        '__construct()' => [
            new PDODriver('sqlite:' . dirname(__DIR__, 2) . '/tests/_output/yiitest.sq3'), // Su ruta de base de datos
        ],
    ],
];
```

## Análisis estático

El código se analiza estáticamente con [Psalm](https://psalm.dev/docs). Para ejecutarlo:

```shell
./vendor/bin/psalm
```

## Pruebas unitarias

Las pruebas unitarias se comprueban con [PHPUnit](https://phpunit.de/). Para ejecutarlo:

```shell
./vendor/bin/phpunit
```

## Calidad y estilo de código

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/126b07f01fea44f69776e987085bb909)](https://www.codacy.com/gh/php-forge/user/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=php-forge/user&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://github.styleci.io/repos/512855391/shield?branch=main)](https://github.styleci.io/repos/512855391?branch=main)

## Licencia

El paquete `forge/user` es software libre. Se publica bajo los términos de la Licencia BSD.
Consulte [`LICENSE`](./LICENSE.md) para obtener más información.

Mantenido por [Terabytesoftw](https://github.com/terabytesoftw).

## Nuestras redes sociales

[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/PhpForge)
