<?php
/**
 * 410 Gone — URL list for botphonic.ai
 */

return [
    'prefixes' => [
        '/wp-content/',
        '/wp-admin/',
    ],

    'regex' => [
        '#^/wp-[^/]+\.php$#i',
        '#^/ai-phone-call/?[^a-zA-Z0-9/_-]#i',
        '#^/ai-receptionist/.+#i',
        '#^\)[^/]#u',
        '#^/PublishedTime:#i',
    ],
    'query_strings' => [
        '/(^|&)fb987752_page=/',
    ],

];
