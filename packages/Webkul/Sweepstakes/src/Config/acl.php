<?php

return [
    [
        'key'    => 'sweepstakes',
        'name'   => 'Sweepstakes',
        'route'  => 'admin.sweepstakes.index',
        'sort'   => 5,
        'icon'   => 'icon-gift', // Optional: Can match the menu icon
    ], [
        'key'    => 'sweepstakes.manage',
        'name'   => 'Manage Sweepstakes',
        'route'  => 'admin.sweepstakes.index',
        'sort'   => 1,
        'parent' => 'sweepstakes',
    ], [
        'key'    => 'sweepstakes.create',
        'name'   => 'Create Sweepstakes',
        'route'  => 'admin.sweepstakes.create',
        'sort'   => 2,
        'parent' => 'sweepstakes.manage',
    ]
    // Add keys for delete, edit, etc., if needed: sweepstakes.manage.delete, sweepstakes.manage.edit
];