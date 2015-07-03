<?php
return array_merge([
    'base_url'         => '',
    'analytics_id'     => null,
    'debug'            => false,
    'disqus_shortname' => 'overdocs',

    'github_repository_url' => 'https://github.com/overdocs/overdocs',
], require(__DIR__ . '/application.' . (getenv('OVERDOCS_ENV') ?: 'development') . '.php'));
