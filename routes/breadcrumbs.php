<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Route;

// admin dashboard
Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});
Breadcrumbs::for('admin.tag.export', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});
Breadcrumbs::for('admin.posts.export', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});
Breadcrumbs::for('admin.files.export', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});

Breadcrumbs::macro('resource', function (string $name, string $title, ?string $parentName = null) {
    if ($parentName) {
        Breadcrumbs::for("{$name}.index", function (BreadcrumbTrail $trail, $model) use ($name, $title, $parentName) {
            $trail->parent("{$parentName}.show", $model);
            $trail->push($title, route("{$name}.index", $model));
        });

        Breadcrumbs::for("{$name}.create", function (BreadcrumbTrail $trail, $model) use ($name) {
            $trail->parent("{$name}.index", $model);
            $trail->push('Create', route("{$name}.create", $model));
        });
    
        Breadcrumbs::for("{$name}.show", function (BreadcrumbTrail $trail, $model, $item) use ($name) {
            $trail->parent("{$name}.index", $model, $item);
            \Log::info("{$name}.show");
            if (Route::has("{$name}.show")) {
                $trail->push($item->name ?? $model, route("{$name}.show", [$model, $item]));
            } else {
                $trail->push($item->name ?? $model);
            }
        });
    
        Breadcrumbs::for("{$name}.edit", function (BreadcrumbTrail $trail, $model, $item) use ($name) {
            $trail->parent("{$name}.show", $model, $item);
            $trail->push('Edit', route("{$name}.edit", [$model, $item]));
        });
        
    } else {
        Breadcrumbs::for("{$name}.index", function (BreadcrumbTrail $trail) use ($name, $title) {
            $trail->parent('admin.dashboard');
            $trail->push($title, route("{$name}.index"));
        });

        Breadcrumbs::for("{$name}.create", function (BreadcrumbTrail $trail) use ($name) {
            $trail->parent("{$name}.index");
            $trail->push('Create', route("{$name}.create"));
        });
    
        Breadcrumbs::for("{$name}.show", function (BreadcrumbTrail $trail, $model) use ($name) {
            $trail->parent("{$name}.index");
            if (Route::has("$name.show")) {
                $trail->push($model->name ?? $model, route("{$name}.show", $model));
            } else {
                $trail->push($model->name ?? $model);
            }
        });
    
        Breadcrumbs::for("{$name}.edit", function (BreadcrumbTrail $trail, $model) use ($name) {
            $trail->parent("{$name}.show", $model);
            $trail->push('Edit', route("{$name}.edit", $model));
        });
    }
});

Breadcrumbs::resource('admin.permission', 'Permissions');
Breadcrumbs::resource('admin.role', 'Roles');
Breadcrumbs::resource('admin.user', 'Users');
Breadcrumbs::resource('admin.media', 'Media');
Breadcrumbs::resource('admin.menu', 'Menu');
Breadcrumbs::resource('admin.posts', 'Post');
Breadcrumbs::resource('admin.tag', 'Tag');
Breadcrumbs::resource('admin.files', 'Files');
Breadcrumbs::resource('admin.comments', 'Comments');
Breadcrumbs::resource('admin.menu.item', 'Menu Items', 'admin.menu');
Breadcrumbs::resource('admin.category.type', 'Category Types');
Breadcrumbs::resource('admin.category.type.item', 'Items', 'admin.category.type');
Breadcrumbs::resource('admin.posts.create', 'Create', 'admin.posts.index');
Breadcrumbs::resource('admin.posts.index', 'Index', 'admin.posts.index');
Breadcrumbs::resource('admin.posts.edit', 'Edit', 'admin.posts.index');
Breadcrumbs::resource('admin.tag.create', 'Create', 'admin.tag.index');
Breadcrumbs::resource('admin.tag.index', 'Index', 'admin.tag.index');
Breadcrumbs::resource('admin.tag.edit', 'Edit', 'admin.tag.index');
Breadcrumbs::resource('admin.files.edit', 'Edit', 'admin.files.index');

// admin account Info
Breadcrumbs::for('admin.account.info', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Account Info', route('admin.account.info'));
});