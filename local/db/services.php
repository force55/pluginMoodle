<?php
// We defined the web service functions to install.
$functions = array(
    'local_local_get_users' => array(
        'classname' => 'local_local_external',
        'methodname' => 'get_users',
        'classpath' => 'local/local/externallib.php',
        'description' => 'Return users',
        'type' => 'read',
    ),
    'local_local_get_courses' => array(
        'classname' => 'local_local_external',
        'methodname' => 'get_courses',
        'classpath' => 'local/local/externallib.php',
        'description' => 'Return courses',
        'type' => 'read',
    ),
    'local_local_get_enrolled_users' => array(
        'classname' => 'local_local_external',
        'methodname' => 'get_enrolled_users',
        'classpath' => 'local/local/externallib.php',
        'description' => 'Return enrolled users',
        'type' => 'read',
    ),

);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
    'Local plugin service' => array(
        'functions' => array('local_local_get_users','local_local_get_courses','local_local_get_enrolled_users'),
        'enabled' => 1,
    )
);