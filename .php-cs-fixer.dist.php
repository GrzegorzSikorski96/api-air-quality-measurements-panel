<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'psr_autoloading' => true,
        'global_namespace_import'=> [
            'import_classes' => true
        ]
    ])
    ->setFinder($finder)
    ;
