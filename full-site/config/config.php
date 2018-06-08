<?php

Config::set('site_name', 'Zavireno');

Config::set('languages', array('en', 'fr'));

// Routes. Route name => method prefix
Config::set('routes', array(
    'default' => '',
    'admin'   => 'admin_',
    'user'    => 'user_',
    'notary'        => 'notary_',
));

Config::set('default_route', 'default');
Config::set('default_language', 'en');
Config::set('default_controller', 'main'); //main
Config::set('default_action', 'index');

Config::set('db.host', 'customsh.mysql.tools');
Config::set('db.user', 'customsh_notar');
Config::set('db.password', '2yud9j7w');
Config::set('db.db_name', 'customsh_notar');

Config::set('salt', 'jd7sj3sdkd964he7e');