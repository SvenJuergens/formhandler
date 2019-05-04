<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "formhandler".
 *
 * Auto generated 05-03-2014 13:22
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Formhandler',
    'description' => 'The swiss army knife for all kinds of mailforms, completely new written using the MVC concept. Result: Flexibility, Flexibility, Flexibility  :-).',
    'category' => 'plugin',
    'shy' => 0,
    'version' => '3.0.0',
    'state' => 'stable',
    'clearcacheonload' => 1,
    'author' => 'Dev-Team Typoheads',
    'author_email' => 'dev@typoheads.at',
    'author_company' => 'Typoheads GmbH',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.5.99',
            'typo3db_legacy' => '1.1.1-1.2.0'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
