<?php


defined('BASEPATH') or exit('No direct script access allowed');




$consentContacts = get_option('gdpr_enable_consent_for_contacts');
$aColumns        = [ ];


$aColumns = array_merge($aColumns, [
    db_prefix() .'cars.car_name as car_name',
    db_prefix() .'brand.brand_name as brand_name',
    db_prefix() .'brand_model.model_name as model_name',
    db_prefix() .'cars.model_year as model_year',
    db_prefix() .'cars.plate_source as plate_source',
    db_prefix() .'cars.plate_code as plate_code',
    db_prefix() .'cars.plate_number as plate_number',
    db_prefix() .'cars.id as id',
]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'cars';
$join         = ['INNER JOIN ' . db_prefix() . 'brand '  . 'ON ' . db_prefix() .'cars.id_brand =' .db_prefix() .'brand.id INNER JOIN ' .db_prefix() .'brand_model ON ' .db_prefix() .'brand_model.id = ' .db_prefix() .'cars.id_model'   ];

$custom_fields = get_table_custom_fields('cars');

// foreach ($custom_fields as $key => $field) {
//     $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
//     array_push($customFieldsColumns, $selectAs);
//     array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
//     array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'contacts.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
// }

$where = [];

// if (!has_permission('customers', '', 'view')) {
//     array_push($where, 'AND ' . db_prefix() . 'contacts.userid IN (SELECT customer_id FROM ' . db_prefix() . 'customer_admins WHERE staff_id=' . get_staff_user_id() . ')');
// }

// if ($this->ci->input->post('custom_view')) {
//     $filter = $this->ci->input->post('custom_view');
//     if (startsWith($filter, 'consent_')) {
//         array_push($where, 'AND ' . db_prefix() . 'contacts.id IN (SELECT contact_id FROM ' . db_prefix() . 'consents WHERE purpose_id=' . $this->ci->db->escape_str(strafter($filter, 'consent_')) . ' and action="opt-in" AND date IN (SELECT MAX(date) FROM ' . db_prefix() . 'consents WHERE purpose_id=' . $this->ci->db->escape_str(strafter($filter, 'consent_')) . ' AND contact_id=' . db_prefix() . 'contacts.id))');
//     }
// }

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join);


$output  = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
    $row = [];  

    $row[] = $aRow['car_name'];


    $row[] = $aRow['brand_name'];

    $row[] = $aRow['model_name'];
    
    $row[] = $aRow['model_year'];
    $row[] = $aRow['plate_source'];

    $row[] = $aRow['plate_code'];
    $row[] = $aRow['plate_number'];

    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;


}

