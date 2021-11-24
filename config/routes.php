<?php

return array(
    // Debounce
    '^user/userSearch' => 'search/userSearch',
    // Debounce

    // Profile
    '^profile/delete/([0-9]+)' => 'user/profileDelete/$1',
    '^profile/edit/([0-9]+)' => 'user/profileEdit/$1',
    // Profile

    // Access system
    '^logout' => 'user/logout',
    '^login' => 'user/login',
    '^register' => 'user/register',
    // Access system

    '^image/send' => 'user/uploadFile',
    '^delete/chat' => 'site/deleteChat',
    '^insert/chat' => 'site/insertChat',
    '^send/chat' => 'site/sendChat',
    '^select/chat' => 'site/selectChat',
    '' => 'site/index',
);