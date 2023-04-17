<?php

defined('BASEPATH') or exit('No direct script access allowed');


// $total_client_contacts = total_rows(db_prefix() . 'cars', ['id_customer' => $client_id]);
$total_client_contacts = total_rows(db_prefix() . 'cars', ['id_customer' => $client_id]);
$this->ci->load->model('gdpr_model');


$consentContacts = get_option('gdpr_enable_consent_for_contacts');
// $aColumns        = [ 'CONCAT(firstname, \' \', lastname) as full_name'];
$aColumns        = [];
// if (is_gdpr() && $consentContacts == '1') {
//     array_push($aColumns, '1');
// }
$aColumns = array_merge( $aColumns , [
    db_prefix() .'cars.car_name as car_name',
    db_prefix() .'cars.id_brand as car_id_brand',
    db_prefix() .'cars.id_model as id_model',
    db_prefix() .'cars.model_year as model_year',
    db_prefix() .'cars.plate_source as plate_source',
    db_prefix() .'cars.plate_code as plate_code',
    db_prefix() .'cars.plate_number as plate_number',
]);
    // db_prefix() .'brand.brand_name as brand_name',
    // db_prefix() .'brand_model.model_name as model_name',
    // db_prefix() . 'cars.id as id',
    // db_prefix() .'cars.id_customer as id_customer',

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'cars';
$join         = ['INNER JOIN ' . db_prefix() . 'brand '  . 'ON ' . db_prefix() .'cars.id_brand =' .db_prefix() .'brand.id INNER JOIN ' .db_prefix() .'brand_model ON ' .db_prefix() .'brand_model.id = ' .db_prefix() .'cars.id_model'   ];

$custom_fields = get_table_custom_fields('cars');



foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'cars.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

$where = ['AND '.db_prefix().'cars.id_customer=' . $this->ci->db->escape_str($client_id)];



// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}



$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where );


// car_name	brand	model	model_year	

// var_dump("d" );
// exit();


$output  = $result['output'];
$rResult = $result['rResult'];



foreach ($rResult as $aRow) {

    $row = [];

    $rowName = /*'<img src="' . contact_profile_image_url($aRow['id']) . '" class="client-profile-image-small mright5">*/'<a href="#" onclick="contact_car(' . $aRow['id_customer'] . ',' . $aRow['id'] . ');return false;">' . $aRow['car_name'] . '</a>';

    $rowName .= '<div class="row-options Xtw-ml-9">';

    $rowName .= '<a href="#" onclick="contact_car(' . $aRow['id_customer'] . ',' . $aRow['id'] . ');return false;">' . _l('edit') . '</a>';

    if (is_gdpr() &&  is_admin()) {
        $rowName .= ' | <a href="' . admin_url('clients/export/' . $aRow['id']) . '">
             ' . _l('dt_button_export') . ' (' . _l('gdpr_short') . ')
          </a>';
    }

    if (has_permission('customers', '', 'delete') || is_customer_admin($aRow['id_customer'])) {
            $rowName .= ' | <a href="' . admin_url('clients/delete_car/' . $aRow['id_customer'] . '/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $rowName .= '</div>';
  

    $row[] = $rowName;

  

    // if (is_gdpr() && $consentContacts == '1') {
    //     $consentHTML = '<p class="bold"><a href="#" onclick="view_contact_consent(' . $aRow['id'] . '); return false;">' . _l('view_consent') . '</a></p>';
    //     $consents    = $this->ci->gdpr_model->get_consent_purposes($aRow['id'], 'contact');
    //     foreach ($consents as $consent) {
    //         $consentHTML .= '<p style="margin-bottom:0px;">' . $consent['name'] . (!empty($consent['consent_given']) ? '<i class="fa fa-check text-success pull-right"></i>' : '<i class="fa fa-remove text-danger pull-right"></i>') . '</p>';
    //     }
    //     $row[] = $consentHTML;
    // }

    $row[] = $aRow['brand_name'];

    $row[] = $aRow['model_name'];
    
    $row[] = $aRow['model_year'];
    $row[] = $aRow['plate_source'];

    $row[] = $aRow['plate_code'];
    $row[] = $aRow['plate_number'];


    

    // Custom fields add values
    // foreach ($customFieldsColumns as $customFieldColumn) {
    //     $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    // }

    // $row['DT_RowClass'] = 'has-row-options';
    // $output['aaData'][] = $row;



}