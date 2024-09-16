<?php return array(
    'root' => array(
        'name' => 'ezyang/htmlpurifier',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => NULL,
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'cerdic/css-tidy' => array(
            'pretty_version' => 'v2.1.0',
            'version' => '2.1.0.0',
            'reference' => '50e4e31adfde7fdb08d815a7dc52c3370596f4e7',
            'type' => 'library',
            'install_path' => __DIR__ . '/../cerdic/css-tidy',
            'aliases' => array(),
            'dev_requirement' => true,
        ),
        'ezyang/htmlpurifier' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'reference' => NULL,
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'lastcraft/simpletest' => array(
            'dev_requirement' => true,
            'replaced' => array(
                0 => '9999999-dev',
                1 => 'dev-master',
            ),
        ),
        'simpletest/simpletest' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => '85526a6a3b898963ba44fad354af988ef62e58fa',
            'type' => 'library',
            'install_path' => __DIR__ . '/../simpletest/simpletest',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => true,
        ),
        'vierbergenlars/simpletest' => array(
            'dev_requirement' => true,
            'replaced' => array(
                0 => '9999999-dev',
                1 => 'dev-master',
            ),
        ),
    ),
);
