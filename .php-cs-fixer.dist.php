<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__) // Сканирование всех директорий
    ->exclude(['var', 'vendor']) // Исключаем директории
    ->name('*.php') // Выбираем только файлы php
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true, // Набор правил для фреймворка Symfony
        '@PSR12' => true, // Подключаем стандарт PSR12 для форматирования стиля кода
    ])
    ->setFinder($finder);
