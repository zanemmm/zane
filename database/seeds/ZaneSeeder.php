<?php

use Illuminate\Database\Seeder;
use \Encore\Admin\Auth\Database\{Administrator, Menu, Permission, Role};
use \Encore\Admin\Config\ConfigModel;

class ZaneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        //create a permission

        Permission::insert([
            [
                'name'        => 'All permission',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => 'Dashboard',
                'slug'        => 'dashboard',
                'http_method' => 'GET',
                'http_path'   => '/',
            ],
            [
                'name'        => 'Login',
                'slug'        => 'auth.login',
                'http_method' => '',
                'http_path'   => "/auth/login\r\n/auth/logout",
            ],
            [
                'name'        => 'User setting',
                'slug'        => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path'   => '/auth/setting',
            ],
            [
                'name'        => 'Auth management',
                'slug'        => 'auth.management',
                'http_method' => '',
                'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ],
            [
                'name'      => 'Media',
                'slug'      => 'ext.media-manager',
                'http_method' => '',
                'http_path' =>  '/media*',
            ],
            [
                'name'      => 'Config',
                'slug'      => 'ext.config',
                'http_method' => '',
                'http_path' =>  '/config*',
            ],
            [
                'name'      => 'Backup',
                'slug'      => 'ext.backup',
                'http_method' => '',
                'http_path' =>  '/backup*',
            ],
        ]);
        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::insert([
            [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => '首页',
                'icon'      => 'fa-dashboard',
                'uri'       => '/',
            ],
            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => '分类管理',
                'icon'      => 'fa-archive',
                'uri'       => 'categories',
            ],
            [
                'parent_id' => 0,
                'order'     => 3,
                'title'     => '文章管理',
                'icon'      => 'fa-file-text',
                'uri'       => 'posts',
            ],
            [
                'parent_id' => 0,
                'order'     => 4,
                'title'     => '标签管理',
                'icon'      => 'fa-tags',
                'uri'       => 'tags',
            ],
            [
                'parent_id' => 0,
                'order'     => 5,
                'title'     => '文件管理',
                'icon'      => 'fa-file',
                'uri'       => 'media',
            ],
            [
                'parent_id' => 0,
                'order'     => 6,
                'title'     => '网站设置',
                'icon'      => 'fa-toggle-on',
                'uri'       => 'config',
            ],
            [
                'parent_id' => 0,
                'order'     => 7,
                'title'     => '后台管理',
                'icon'      => 'fa-tasks',
                'uri'       => '',
            ],
            [
                'parent_id' => 7,
                'order'     => 8,
                'title'     => '用户',
                'icon'      => 'fa-users',
                'uri'       => 'auth/users',
            ],
            [
                'parent_id' => 7,
                'order'     => 9,
                'title'     => '角色',
                'icon'      => 'fa-user',
                'uri'       => 'auth/roles',
            ],
            [
                'parent_id' => 7,
                'order'     => 10,
                'title'     => '权限',
                'icon'      => 'fa-ban',
                'uri'       => 'auth/permissions',
            ],
            [
                'parent_id' => 7,
                'order'     => 11,
                'title'     => '菜单',
                'icon'      => 'fa-bars',
                'uri'       => 'auth/menu',
            ],
            [
                'parent_id' => 7,
                'order'     => 12,
                'title'     => '日志',
                'icon'      => 'fa-history',
                'uri'       => 'auth/logs',
            ],
            [
                'parent_id' => 7,
                'order'     => 13,
                'title'     => '备份',
                'icon'      => 'fa-copy',
                'uri'       => 'backup',
            ],
        ]);
        // add role to menu.
        Menu::find(7)->roles()->save(Role::first());

        ConfigModel::insert([
            [
                'name' => 'site.name',
                'value' => "Zane's Blog",
                'description' => '网站名称'
            ],
            [
                'name' => 'site.author',
                'value' => "zane",
                'description' => '文章作者'
            ],
            [
                'name' => 'site.title',
                'value' => 'Blog',
                'description' => '网站标题'
            ],
            [
                'name' => 'site.intro',
                'value' => 'Just For Fun!',
                'description' => '网站标语'
            ],
            [
                'name' => 'site.keywords',
                'value' => 'blog',
                'description' => '网站关键字'
            ],
            [
                'name' => 'site.description',
                'value' => 'just a blog.',
                'description' => '网站描述'
            ],
            [
                'name' => 'site.footer',
                'value' => 'Powered by Zane.',
                'description' => '网站底部'
            ],
            [
                'name' => 'site.pagination',
                'value' => '5',
                'description' => '分页数量'
            ]
        ]);
    }
}
