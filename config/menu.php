<?php

/*
 | Admin sidebar menu definition.
 | Each entry is a section: a `label` (null = no header / always-open top group),
 | a `collapsible` flag, and a list of `items`.
 | Each item: label, icon (bootstrap-icons class), route name, optional `active`
 | route pattern (defaults to the route name), and optional `can` permission.
 | Items the user lacks permission for are hidden; empty sections disappear.
 */

return [
    [
        'label' => null,
        'collapsible' => false,
        'items' => [
            ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'admin.dashboard'],
            ['label' => 'POS', 'icon' => 'bi-cart-check', 'route' => 'admin.pos.index', 'active' => 'admin.pos.*', 'can' => 'access pos'],
        ],
    ],
    [
        'label' => 'Catalog',
        'collapsible' => true,
        'items' => [
            ['label' => 'Products', 'icon' => 'bi-box', 'route' => 'admin.products.index', 'active' => 'admin.products.*', 'can' => 'view products'],
            ['label' => 'Categories', 'icon' => 'bi-diagram-3', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*', 'can' => 'manage categories'],
            ['label' => 'Stock', 'icon' => 'bi-clipboard-data', 'route' => 'admin.stock.index', 'active' => 'admin.stock.*', 'can' => 'view stock'],
        ],
    ],
    [
        'label' => 'Transactions',
        'collapsible' => true,
        'items' => [
            ['label' => 'Purchases', 'icon' => 'bi-bag-plus', 'route' => 'admin.purchases.index', 'active' => 'admin.purchases.*', 'can' => 'view purchases'],
            ['label' => 'Sales', 'icon' => 'bi-receipt', 'route' => 'admin.sales.index', 'active' => 'admin.sales.*', 'can' => 'view sales'],
        ],
    ],
    [
        'label' => 'Reports',
        'collapsible' => true,
        'items' => [
            ['label' => 'Sales & Profit', 'icon' => 'bi-graph-up', 'route' => 'admin.reports.sales', 'can' => 'view reports'],
            ['label' => 'Daily Summary', 'icon' => 'bi-calendar3', 'route' => 'admin.reports.daily', 'can' => 'view reports'],
            ['label' => 'Top Products', 'icon' => 'bi-trophy', 'route' => 'admin.reports.products', 'can' => 'view reports'],
            ['label' => 'Purchases', 'icon' => 'bi-bag-check', 'route' => 'admin.reports.purchases', 'can' => 'view reports'],
            ['label' => 'Stock Valuation', 'icon' => 'bi-cash-stack', 'route' => 'admin.reports.stock', 'can' => 'view reports'],
        ],
    ],
    [
        'label' => 'Contacts',
        'collapsible' => true,
        'items' => [
            ['label' => 'Suppliers', 'icon' => 'bi-truck', 'route' => 'admin.suppliers.index', 'active' => 'admin.suppliers.*', 'can' => 'manage suppliers'],
            ['label' => 'Customers', 'icon' => 'bi-people', 'route' => 'admin.customers.index', 'active' => 'admin.customers.*', 'can' => 'manage customers'],
        ],
    ],
    [
        'label' => 'Access Control',
        'collapsible' => true,
        'items' => [
            ['label' => 'Users', 'icon' => 'bi-person-gear', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'can' => 'manage users'],
            ['label' => 'Roles', 'icon' => 'bi-shield-lock', 'route' => 'admin.roles.index', 'active' => 'admin.roles.*', 'can' => 'manage roles'],
            ['label' => 'Permissions', 'icon' => 'bi-key', 'route' => 'admin.permissions.index', 'active' => 'admin.permissions.*', 'can' => 'manage permissions'],
        ],
    ],
];
