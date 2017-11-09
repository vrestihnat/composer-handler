<?php

date_default_timezone_set('Europe/Prague');
/* ini_set('max_execution_time', '30');
  ini_set('default_socket_timeout', '120');
  ini_set('post_max_size', '30');
  ini_set('max_file_uploads', '20');
  ini_set('memory_limit', '512M'); */
mb_internal_encoding('utf-8');
setlocale(LC_ALL, 'cs_CZ.UTF-8');

$MAX_LINES_TERMINAL=300;
$DOCUMENT_ROOT_TERMINAL='/var/www';
return [
    'executor' => [
        0 => '/usr/bin/composer',
        1 => '/bin/bash'
    ],
    'actions' => [
        0 => [
            0 => 'install',
            1 => 'update',
            2 => 'update',
            3 => ''
        ],
        1 => [
            0 => ''
        ],
    ],
    'shellCommands' => [
        'testEnd' => 'cat /tmp/%s | grep "|||:::|||konec" | wc -l',
        'showTerminal' => 'tail -n' . $MAX_LINES_TERMINAL . ' /tmp/%s',
        'runCommand' => 'nohup /bin/bash ' . APP_DIR . '/scripts/wrapper.sh "cd '.$DOCUMENT_ROOT_TERMINAL.'/%s && %s %s %s" /tmp/%s >> /tmp/%s &',
    ],
    'terminalRefresh' => 250,
];
