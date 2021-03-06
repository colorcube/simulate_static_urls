<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'simulate_static_urls',
    'description' => 'Speaking urls without the hassle',
    'category' => 'fe',
    'state' => 'stable',
    'author' => 'Rene Fritz',
    'author_email' => 'r.fritz@colorcube.de',
    'author_company' => 'Colorcube',
    'version' => '1.1.2',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-8.99.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Colorcube\\SimulateStaticUrls\\' => 'Classes'
        ]
    ]
];
