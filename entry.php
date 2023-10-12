<?php

/**
 * |-------------------------------------------------------
 * | Входная точка фреймворка
 * |-------------------------------------------------------
 * | Этот файл должен подключаться каждым приложением,
 * | построенным на базе Uwi.
 * |
 * | Автор:         Alexandr Shamanin (@slpAkkie)
 * | Репозиторий:   https://github.com/slpAkkie/Uwi.git
 * |
 * | Лицензия:      Вы не можете изменять и распространять
 * |                этот файл и любые другие файлы в этом
 * |                репозитории без разрешения автора.
 * |-------------------------------------------------------
 */

// Подключаем файл начальной загрузки
require_once __DIR__ . '/bootstrap/init.php';

// Регистрируем настройки автозагрузки классов
Uwi\Loader\Loader::fromJson(UWI_DIR . '/Loader.json');

// Попытка зарегистрировать локальные настройки автозагрузки классов
(function () {
    $localLoaderConfigurationPath = LOCAL_DIR . '/Loader.json';
    if (file_exists($localLoaderConfigurationPath))
        Uwi\Loader\Loader::fromJson($localLoaderConfigurationPath);
})();
