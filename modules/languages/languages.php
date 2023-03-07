<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Languages
Description: add new language .
Version: 1.0.5
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
*/


define('LANAGUAGES_MODULE_NAME', 'languages');
define('LANAGUAGES_MODULE_UPLOAD_FOLDER', module_dir_path(LANAGUAGES_MODULE_NAME, 'uploads'));
define('LANAGUAGES_IMPORT_ITEM_ERROR', 'modules/languages/uploads/import_item_error/');
define('LANAGUAGES_ERROR', FCPATH );
define('LANAGUAGES_EXPORT_XLSX', 'modules/languages/uploads/export_xlsx/');

hooks()->add_action('app_admin_head', 'languages_add_head_component');
hooks()->add_action('app_admin_footer', 'languages_load_js');
hooks()->add_action('admin_init', 'languages_module_init_menu_items');
hooks()->add_action('admin_init', 'languages_permissions');
hooks()->add_action('after_invoice_added', 'languages_automatic_invoice_conversion');
hooks()->add_action('after_payment_added', 'languages_automatic_payment_conversion');
hooks()->add_action('after_expense_added', 'languages_automatic_expense_conversion');
hooks()->add_action('before_invoice_deleted', 'languages_delete_invoice_convert');
hooks()->add_action('before_payment_deleted', 'languages_delete_payment_convert');
hooks()->add_action('after_expense_deleted', 'languages_delete_expense_convert');
hooks()->add_action('invoice_status_changed', 'languages_invoice_status_changed');

define('LANAGUAGES_REVISION', 105);


/**
 * Register activation module hook
 */

register_activation_hook(LANAGUAGES_MODULE_NAME, 'languages_module_activation_hook');

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(LANAGUAGES_MODULE_NAME, [LANAGUAGES_MODULE_NAME]);

/**
 * spreadsheet online module activation hook
 */
function languages_module_activation_hook()
{
    $CI = &get_instance();
    require_once __DIR__ . '/install.php';
}


/**
 * init add head component
 */
function languages_add_head_component()
{
    $CI      = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];

    if (!(strpos($viewuri, 'admin/languages/lang/*') === false)) {   
   
        echo '<link href="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/css/manage.css')  .  '"  rel="stylesheet" type="text/css" />';   
    }


  
}

/**
 * init add footer component
 */
function languages_load_js()
{
    $CI          = &get_instance();
    $viewuri     = $_SERVER['REQUEST_URI'];
    $mediaLocale = get_media_locale();

    if (!(strpos($viewuri, 'admin/languages/transaction?group=banking') === false)) {
        echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/js/transaction/banking.js') . '?v=' . LANAGUAGES_REVISION . '"></script>';
    }

    if (!(strpos($viewuri, 'admin/languages/transaction?group=sales') === false)) {
        echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/js/transaction/sales.js') . '?v=' . LANAGUAGES_REVISION . '"></script>';
    }

    if (!(strpos($viewuri, 'admin/languages/transaction?group=expenses') === false)) {
        echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/js/transaction/expenses.js') . '?v=' . LANAGUAGES_REVISION . '"></script>';
    }

    if (!(strpos($viewuri, 'admin/languages/setting?group=general') === false)) {
        echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/js/setting/general.js') . '?v=' . LANAGUAGES_REVISION . '"></script>';
    }

    if (!(strpos($viewuri, 'admin/languages/setting?group=mapping_setup') === false)) {
        echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/js/setting/automatic_conversion.js') . '?v=' . LANAGUAGES_REVISION . '"></script>';
    }

    if (!(strpos($viewuri, 'admin/languages/setting?group=banking_rules') === false)) {
        echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/js/setting/banking_rules.js') . '?v=' . LANAGUAGES_REVISION . '"></script>';
    }

    if (!(strpos($viewuri, 'admin/languages/setting?group=account_type_details') === false)) {
        echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/js/setting/account_type_details.js') . '?v=' . LANAGUAGES_REVISION . '"></script>';
    }

    if (!(strpos($viewuri, 'admin/languages/new_rule') === false)) {
        echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/js/setting/new_rule.js') . '?v=' . LANAGUAGES_REVISION . '"></script>';
    }

    if (!(strpos($viewuri, 'admin/languages/journal_entry') === false)) {
        echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/js/journal_entry/manage.js') . '?v=' . LANAGUAGES_REVISION . '"></script>';
    }
    if (!(strpos($viewuri, 'admin/languages/new_journal_entry') === false)) {
        echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
         echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
    }

    if (!(strpos($viewuri, 'admin/languages/transaction') === false)) {   
         echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
         echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
    }

    if (!(strpos($viewuri, 'admin/languages/reconcile') === false)) {   
        echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/js/reconcile/reconcile.js') . '?v=' . LANAGUAGES_REVISION . '"></script>';
    }

    if(!(strpos($viewuri,'admin/languages/rp_') === false) || !(strpos($viewuri,'admin/languages/report') === false)){
        echo '<script src="'.module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/plugins/treegrid/js/jquery.treegrid.min.js').'?v=' . LANAGUAGES_REVISION.'"></script>';
        echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/js/report/main.js') . '?v=' . LANAGUAGES_REVISION . '"></script>';
    }

    if (!(strpos($viewuri, '/admin/languages/dashboard') === false)) {
    echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
    echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
    echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
    echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
    echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
    echo '<script src="' . module_dir_url(LANAGUAGES_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>'; 
}
}

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function languages_module_init_menu_items()
{
    $CI = &get_instance();

    if (has_permission('languages_dashboard', '', 'view') || has_permission('languages_transaction', '', 'view') || has_permission('languages_journal_entry', '', 'view') || has_permission('languages_transfer', '', 'view') || has_permission('languages_chart_of_accounts', '', 'view') || has_permission('languages_reconcile', '', 'view') || has_permission('languages_report', '', 'view') || has_permission('languages_setting', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('languages', [
            'name'     => _l('als_languages'),
            'icon'     => 'fa fa-folder',
            'position' => 5,
        ]);

        if (has_permission('languages_transaction', '', 'view')) {
            $CI->app_menu->add_sidebar_children_item('languages', [
                'slug'     => 'upload languages',
                'name'     => 'upload languages',
                'icon'     => 'fa fa-handshake-o',
                'href'     => admin_url('languages/upload_lang'),
                'position' => 2,
            ]);
          }
        

        foreach ($CI->app->get_available_languages() as $user_lang) { 
            if (has_permission('languages_transaction', '', 'view')) {
                $CI->app_menu->add_sidebar_children_item('languages', [
                    'slug'     => $user_lang,
                    'name'     => $user_lang,
                    'icon'     => 'fa fa-handshake-o',
                    'href'     => admin_url('languages/lang/'.$user_lang),
                    'position' => 2,
                ]);
              }
            } 

       
    }
}

/**
 * Init languages module permissions in setup in admin_init hook
 */
function languages_permissions() {

    $capabilities = [];
    $capabilities['capabilities'] = [
        'view'   => _l('permission_view'),
    ];
    register_staff_capabilities('languages_dashboard', $capabilities, _l('languages_dashboard'));

    $capabilities = [];
    $capabilities['capabilities'] = [
        'view'   => _l('permission_view'),
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    register_staff_capabilities('languages_transaction', $capabilities, _l('languages_transaction'));

    $capabilities = [];
    $capabilities['capabilities'] = [
        'view'   => _l('permission_view'),
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    register_staff_capabilities('languages_journal_entry', $capabilities, _l('languages_journal_entry'));

    $capabilities = [];
    $capabilities['capabilities'] = [
        'view'   => _l('permission_view'),
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    register_staff_capabilities('languages_transfer', $capabilities, _l('languages_transfer'));

    $capabilities = [];
    $capabilities['capabilities'] = [
        'view'   => _l('permission_view'),
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    register_staff_capabilities('languages_chart_of_accounts', $capabilities, _l('languages_chart_of_accounts'));
    $capabilities = [];
    $capabilities['capabilities'] = [
        'view'   => _l('permission_view'),
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
    ];
    register_staff_capabilities('languages_reconcile', $capabilities, _l('languages_reconcile'));

    $capabilities = [];
    $capabilities['capabilities'] = [
        'view'   => _l('permission_view'),
    ];
    register_staff_capabilities('languages_report', $capabilities, _l('languages_report'));

    $capabilities = [];
    $capabilities['capabilities'] = [
        'view'   => _l('permission_view'),
        'edit'   => _l('permission_edit'),
    ];
    register_staff_capabilities('languages_setting', $capabilities, _l('languages_setting'));
}

function languages_automatic_invoice_conversion($invoice_id){
    if($invoice_id){
        if(get_option('languages_invoice_automatic_conversion') == 1){
            $CI = &get_instance();
            $CI->load->model('languages/languages_model');

            $CI->languages_model->automatic_invoice_conversion($invoice_id);
        }

    }

    return $invoice_id;
}

function languages_automatic_payment_conversion($payment_id){
    if($payment_id){
        if(get_option('languages_payment_automatic_conversion') == 1 || get_option('languages_active_payment_mode_mapping') == 1){
            $CI = &get_instance();
            $CI->load->model('languages/languages_model');

            $CI->languages_model->automatic_payment_conversion($payment_id);
        }

    }

    return $payment_id;
}

function languages_automatic_expense_conversion($expense_id){
    if($expense_id){
        if(get_option('languages_expense_automatic_conversion') == 1 || get_option('languages_active_expense_category_mapping') == 1){
            $CI = &get_instance();
            $CI->load->model('languages/languages_model');
            $CI->languages_model->automatic_expense_conversion($expense_id);
        }

    }
    return $expense_id;
}

function languages_delete_invoice_convert($invoice_id){
    if($invoice_id){
        $CI = &get_instance();
        $CI->load->model('languages/languages_model');

        $CI->languages_model->delete_invoice_convert($invoice_id);

    }

    return $data;
}

function languages_delete_payment_convert($data){
    if($data['paymentid']){
        $CI = &get_instance();
        $CI->load->model('languages/languages_model');

        $CI->languages_model->delete_convert($data['paymentid'], 'payment');
    }

    return $data;
}

function languages_delete_expense_convert($expense_id){
    if($expense_id){
        $CI = &get_instance();
        $CI->load->model('languages/languages_model');

        $CI->languages_model->delete_convert($expense_id, 'expense');
    }

    return $data;
}

function languages_invoice_status_changed($data){
    $CI = &get_instance();
    $CI->load->model('languages/languages_model');

    $CI->languages_model->invoice_status_changed($data);

    return $data;
}
