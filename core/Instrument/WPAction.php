<?php

namespace Instrument;

class WPAction
{
    public static function Init(string $callbackFunctionName)
    {
        add_action('init', $callbackFunctionName);
    }

    /**
     * @param string $role
     * @param string $displayName
     * @param bool[] $capabilities :: ['read'=>true , 'edit_posts'=>false]
     * @return void
     */
    public static function AddRole(string $role, string $displayName, array $capabilities)
    {
        add_action('init', function () use ($role, $displayName, $capabilities) {
            add_role($role, $displayName, $capabilities);
        });
    }

    /**
     * @param string $postSaveCallbackFunctionName
     */
    public static function Save(string $postSaveCallbackFunctionName)
    {
        add_action('save_post', $postSaveCallbackFunctionName);
    }

    /**
     * @param array $customPostTypes
     * @param string $metaBoxName
     * @param string $metaBoxTitle
     * @param string $metaBoxCallbackFunctionName
     * @param string $metaBoxContext
     * @param string $metaBoxPriority
     */
    public static function MetaBox(array $customPostTypes, string $metaBoxName, string $metaBoxTitle, string $metaBoxCallbackFunctionName, string $metaBoxContext, string $metaBoxPriority)
    {
        add_action('add_meta_boxes', function () use ($customPostTypes, $metaBoxName, $metaBoxTitle, $metaBoxCallbackFunctionName, $metaBoxContext, $metaBoxPriority) {
            foreach ($customPostTypes as $postType) {
                add_meta_box($metaBoxName . '_meta_box', $metaBoxTitle, $metaBoxCallbackFunctionName, $postType, $metaBoxContext, $metaBoxPriority);
            }
        });
    }

    /**
     * @param string $tagID
     * @param string $customPostType
     * @param string $context
     */
    public static function RemoveMetaBox(string $tagID, string $customPostType, string $context)
    {
        add_action('admin_menu', function () use ($tagID, $customPostType, $context) {

            remove_meta_box($tagID, $customPostType, $context);

        });
    }

    /**
     * @param string $handle
     * @param string $location :: after /asset/css/...
     * @param array $deps
     * @param string $version
     * @param string $locationType :: 'directory' or 'url'
     * @param bool $addToAdmin
     * @param bool $addToApp
     */
    public static function EnqueueStyle(string $handle, string $location, array $deps = [], string $version = '1.0.0', string $locationType = 'directory', bool $addToAdmin = true, bool $addToApp = false)
    {
        if ($addToAdmin) {

            add_action('admin_enqueue_scripts', function () use ($handle, $location, $deps, $version, $locationType) {
                wp_enqueue_style($handle, (($locationType == 'directory') ? TEMPLATE_STYLESHEETS_URL : '') . $location, $deps, $version);
            });
        }
        if ($addToApp) {
            add_action('wp_enqueue_scripts', function () use ($handle, $location, $deps, $version, $locationType) {
                wp_enqueue_style($handle, (($locationType == 'directory') ? TEMPLATE_STYLESHEETS_URL : '') . $location, $deps, $version);
            });

        }
    }

    /**
     * @param string $handle
     * @param string $location
     * @param array $deps
     * @param string $version
     * @param string $locationType
     */
    public static function LoginStyle(string $handle, string $location, array $deps = [], string $version = '1.0.0', string $locationType = 'directory')
    {
        add_action('login_enqueue_scripts', function () use ($handle, $location, $deps, $version, $locationType) {
            wp_enqueue_style($handle, (($locationType == 'directory') ? TEMPLATE_STYLESHEETS_URL : '') . $location, $deps, $version);
        });
    }

    /**
     * @param string $handle
     * @param string $location :: after /asset/js/...
     * @param array $deps
     * @param string $version
     * @param bool $inFooter
     * @param string $locationType :: 'directory' or 'url'
     * @param bool $addToAdmin
     * @param bool $addToApp
     */
    public static function EnqueueScript(string $handle, string $location, array $deps = [], string $version = '1.0.0', bool $inFooter = true, string $locationType = 'directory', bool $addToAdmin = true, bool $addToApp = false)
    {
        if ($addToAdmin) {
            add_action('admin_enqueue_scripts', function () use ($handle, $location, $deps, $version, $inFooter, $locationType) {
                wp_enqueue_script($handle, (($locationType == 'directory') ? TEMPLATE_SCRIPTS_URL : '') . $location, $deps, $version, $inFooter);
            });
        }
        if ($addToApp) {
            add_action('wp_enqueue_scripts', function () use ($handle, $location, $deps, $version, $inFooter, $locationType) {
                wp_enqueue_script($handle, (($locationType == 'directory') ? TEMPLATE_SCRIPTS_URL : '') . $location, $deps, $version, $inFooter);
            });
        }

    }

    /**
     * @param string $postSaveCallbackFunctionName
     */
    public static function MISC(string $postSaveCallbackFunctionName)
    {
        add_action('post_submitbox_misc_actions', $postSaveCallbackFunctionName);
    }

    /**
     * @param string $handle :: نام اسکریپتی که کنارش میخوایم اجرا بشه
     * @param string $objectName
     * @param array $data
     */
    public static function DashboardLocalize(string $handle, string $objectName, array $data)
    {
        add_action('admin_enqueue_scripts', function () use ($handle, $objectName, $data) {
            wp_localize_script($handle, $objectName, $data);
        });
    }

    /**
     * @param string $handle
     * @param string $objectName
     * @param array $data
     * @return void
     */
    public static function AppLocalize(string $handle, string $objectName, array $data)
    {
        add_action('wp_enqueue_scripts', function () use ($handle, $objectName, $data) {
            wp_localize_script($handle, $objectName, $data);
        });
    }

    /**
     * @param string $pageTitle
     * @param string $menuTitle
     * @param string $capability
     * @param string $menuSlug
     * @param string $callBackFunction
     * @param string $icon
     * @param int|null $position
     * @return void
     */
    public static function AddMenuPage(string $pageTitle, string $menuTitle, string $capability, string $menuSlug, string $callBackFunction = '', string $icon = '', int $position = null)
    {
        add_action('admin_menu', function () use ($pageTitle, $menuTitle, $capability, $menuSlug, $callBackFunction, $icon, $position) {
            add_menu_page($pageTitle, $menuTitle, $capability, $menuSlug, $callBackFunction, $icon, $position);
        });
    }

    /**
     * @param string $parentSlug
     * @param string $pageTitle
     * @param string $menuTitle
     * @param string $capability
     * @param string $menuSlug
     * @param string $callBackFunction
     * @param int|null $position
     */
    public static function AddSubMenu(string $parentSlug, string $pageTitle, string $menuTitle, string $capability, string $menuSlug, string $callBackFunction = '', int $position = null)
    {
        add_action('admin_menu', function () use ($parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, $callBackFunction, $position) {
            add_submenu_page($parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, $callBackFunction, $position);
        });
    }

    /**
     * @param string $menuSlug
     */
    public static function RemoveMenu(string $menuSlug)
    {
        add_action('admin_menu', function () use ($menuSlug) {
            remove_menu_page($menuSlug);
        });
    }

    /**
     * @param string $menuSlug
     * @param string $submenuSlug
     */
    public static function RemoveSubMenu(string $menuSlug, string $submenuSlug)
    {
        add_action('admin_menu', function () use ($menuSlug, $submenuSlug) {
            remove_submenu_page($menuSlug, $submenuSlug);
        });
    }

    /**
     * @param string $labelName
     * @param string $slug
     * @param string $description
     * @param string $icon
     * @param bool $titleEditor
     * @param bool $contentEditor
     * @param bool $thumbnail
     * @param bool $comments
     * @return void
     */
    public static function AddCustomPostType(string $labelName, string $slug, string $description, string $icon = 'dashicons-arrow-left', bool $titleEditor = false, bool $contentEditor = false, bool $thumbnail = false, bool $comments = true)
    {
        $supports = [];
        if ($titleEditor)
            $supports[] = 'title';
        if ($contentEditor)
            $supports[] = 'editor';
        if ($thumbnail)
            $supports[] = 'thumbnail';
        if ($comments)
            $supports[] = 'comments';

        $labels = [
            'name' => $labelName,
            'singular_name' => $labelName,
            'menu_name' => $labelName,
            'name_admin_bar' => $labelName,
            'add_new' => $labelName . ' جدید',
            'add_new_item' => 'آیتم ' . $labelName . ' جدید',
            'new_item' => $labelName . ' جدید',
            'edit_item' => 'ویرایش ' . $labelName,
            'view_item' => 'نمایش ' . $labelName,
            'all_items' => 'تمام ' . $labelName . '‌ها',
            'search_items' => 'جستجوی ' . $labelName . '‌ها',
            'parent_item_colon' => $labelName . ' مادر :',
            'not_found' => $labelName . ' یافت نشد',
            'not_found_in_trash' => $labelName . ' در زباله دان یافت نشد',
            'item_published' => $labelName . ' منتشر شد',
            'item_scheduled' => $labelName . ' زمان‌بندی شد',
            'item_updated' => $labelName . ' به‌روزرسانی شد',
        ];

        register_post_type($slug, [
            'labels' => $labels,
            'rewrite' => ['slug' => $slug],
            'description' => $description,
            'menu_icon' => $icon,
            'supports' => count($supports) > 0 ? $supports : [''],
            'capability_type' => [$slug, $slug . 's'],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'map_meta_cap' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 5,
        ]);
    }

    /**
     * @param string $labelName
     * @param string $slug
     * @param string[] $customPostTypes :: ['client']
     * @return void
     */
    public static function AddCustomTaxonomy(string $labelName, string $slug, array $customPostTypes)
    {
        $labels = [
            'name' => $labelName,
            'singular_name' => $labelName,
            'search_items' => 'جستجوی ' . $labelName,
            'all_items' => 'تمام ' . $labelName . '‌ها',
            'parent_item' => 'آیتم ' . $labelName,
            'parent_item_colon' => $labelName . ' مادر :',
            'edit_item' => 'ویرایش ' . $labelName,
            'update_item' => 'بروزرسانی ' . $labelName,
            'add_new_item' => 'آیتم ' . $labelName . ' جدید',
            'new_item_name' => 'نام ' . $labelName . ' جدید',
            'menu_name' => 'افزودن ' . $labelName,
        ];
        $args = [
            'hierarchical' => true,
            'capabilities' => [
                'manage_terms' => 'manage_' . $slug,
                'edit_terms' => 'edit_' . $slug,
                'delete_terms' => 'delete_' . $slug,
                'assign_terms' => 'assign_' . $slug,
            ],
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => $slug],
        ];
        register_taxonomy($slug, $customPostTypes, $args);
    }


}