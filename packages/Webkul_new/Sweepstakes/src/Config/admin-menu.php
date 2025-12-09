<?php

return [
    [
        'key'         => 'sweepstakes',
        'name'        => 'sweepstakes::app.components.layouts.sidebar.Sweepstakes',
        'route'       => 'admin.sweepstakes.index',
        'sort'        => 2, // Use a higher sort number to place it lower in the menu
        'icon'        => 'fa-brands fa-freebsd', // Using a suitable default icon, change if needed
    ], 
	// [
        // 'key'         => 'sweepstakes.sweepstakes-manager',
        // 'name'        => 'sweepstakes::app.components.layouts.sidebar.SweepstakesManager',
        // 'route'       => 'admin.sweepstakes.index', // This points to the main listing page
        // 'sort'        => 1,
        // 'icon'        => 'icon-dashboard', // Not strictly needed for sub-menus, but often added for clarity
        // 'parent'      => 'sweepstakes',
    // ], 
	[
        'key'         => 'sweepstakes.entries',
        'name'        => 'sweepstakes::app.components.layouts.sidebar.EntriesViewer',
        'route'       => 'admin.sweepstakes.entries.index', // Assuming a separate route for viewing entries
        'sort'        => 2,
		'icon'        => '',
        'parent'      => 'sweepstakes',
    ], [
        'key'         => 'sweepstakes.settings',
        'name'        => 'sweepstakes::app.components.layouts.sidebar.Settings',
        'route'       => 'admin.sweepstakes.settings.index', // A route for general module settings/scheduler
        'sort'        => 3,
		'icon'        => '',
        'parent'      => 'sweepstakes',
    ]
];