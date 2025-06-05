<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/public',
        __DIR__ . '/templates',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);
$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'no_unused_imports' => true,
    'single_quote' => true,
    'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters']],
    'binary_operator_spaces' => [
        'default' => 'single_space',
    ],
    'blank_line_before_statement' => [
        'statements' => ['return', 'throw', 'try', 'if', 'foreach', 'while', 'do', 'switch'],
    ],
    'concat_space' => ['spacing' => 'one'], // Espaço ao redor do operador de concatenação '.'
])
->setFinder($finder)
->setIndent("    ")
->setLineEnding("\n");