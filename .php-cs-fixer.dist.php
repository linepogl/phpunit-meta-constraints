<?php

$config = new PhpCsFixer\Config();
$config->setUnsupportedPhpVersionAllowed(true);
$config->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect());
return $config
    ->setCacheFile('var/cache/.phpcsfix/.php-cs-fixer.cache')
    ->setRiskyAllowed(true)
    ->setFinder(new PhpCsFixer\Finder()->in([__DIR__]))
    ->setRules([
        '@PHP8x0Migration' => true,
        '@PSR12' => true,
        '@PER-CS' => true,
        'array_indentation' => true,
        'trim_array_spaces' => true,
        'binary_operator_spaces' => ['default' => 'single_space'],
        'concat_space' => ['spacing' => 'one'],
        'single_line_empty_body' => false,
        'global_namespace_import' => true,
        'group_import' => false,
        'heredoc_indentation' => false,
        'lambda_not_used_import' => true,
        'no_trailing_comma_in_singleline' => true,
        'no_unused_imports' => true,
        'ordered_imports' => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'alpha'],
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'single_import_per_statement' => ['group_to_single_imports' => true],
        'standardize_not_equals' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters', 'match']],
        'array_push' => true,
        'combine_nested_dirname' => true,
        'dir_constant' => true,
        'modernize_strpos' => true,
        'native_function_invocation' => ['include' => [], 'strict'  => true],
        'non_printable_character' => true,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'static'],
        'static_lambda' => true,
        'declare_strict_types' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'yoda_style' => true,
        'new_expression_parentheses' => ['use_parentheses' => false],
        'no_extra_blank_lines' => ['tokens' => ['extra','use','switch','case','default','curly_brace_block','parenthesis_brace_block','square_brace_block']],
    ]);
