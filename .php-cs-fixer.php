<?php

$finder = PhpCsFixer\Finder::create()
    ->in(['src', 'tests', 'config', 'migrations', 'bin'])
    ->exclude(['_generated', '_output'])
    ->ignoreVCS(true)
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PHP80Migration' => true,
        '@PHP81Migration' => true,
        '@PHP82Migration' => true,
        // добавь/убери нужные правила:
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => false,
    ])
    ->setFinder($finder);
