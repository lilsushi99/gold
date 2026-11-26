<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /** slug => [label, group] */
    public const PERMISSIONS = [
        'dashboard.view'     => ['View dashboard', 'General'],
        'pages.manage'       => ['Manage pages', 'Content'],
        'sections.manage'    => ['Manage page sections', 'Content'],
        'posts.manage'       => ['Manage blog posts, categories and tags', 'Content'],
        'content.manage'     => ['Manage services, portfolio, team, testimonials, FAQs, pricing', 'Content'],
        'media.manage'       => ['Manage media library', 'Content'],
        'menus.manage'       => ['Manage navigation menus', 'Content'],
        'submissions.manage' => ['Manage contact submissions', 'Content'],
        'seo.manage'         => ['Manage SEO metadata', 'SEO'],
        'sitemap.manage'     => ['Manage XML sitemap', 'SEO'],
        'robots.manage'      => ['Edit robots.txt', 'SEO'],
        'redirects.manage'   => ['Manage redirects', 'SEO'],
        'settings.manage'    => ['Manage website settings', 'System'],
        'users.manage'       => ['Manage users, roles and permissions', 'System'],
        'logs.view'          => ['View activity logs', 'System'],
        'database.manage'    => ['Database and backup tools', 'System'],
    ];

    public const ROLES = [
        'super-admin' => ['Super Administrator', 'Full access to everything.', '*'],
        'content-admin' => ['Content Administrator', 'Manages pages, sections, blog, media and content modules.', [
            'dashboard.view', 'pages.manage', 'sections.manage', 'posts.manage', 'content.manage',
            'media.manage', 'menus.manage', 'submissions.manage',
        ]],
        'seo-admin' => ['SEO Administrator', 'Manages SEO metadata, sitemap, robots.txt and redirects.', [
            'dashboard.view', 'seo.manage', 'sitemap.manage', 'robots.manage', 'redirects.manage',
        ]],
    ];

    public function run(): void
    {
        foreach (self::PERMISSIONS as $slug => [$label, $group]) {
            Permission::updateOrCreate(['slug' => $slug], ['label' => $label, 'group' => $group]);
        }

        $all = Permission::pluck('id', 'slug');

        foreach (self::ROLES as $slug => [$name, $description, $perms]) {
            $role = Role::updateOrCreate(['slug' => $slug], [
                'name' => $name, 'description' => $description, 'is_system' => true,
            ]);
            $ids = $perms === '*' ? $all->values() : collect($perms)->map(fn ($p) => $all[$p]);
            $role->permissions()->syncWithoutDetaching($ids->all());
        }
    }
}
