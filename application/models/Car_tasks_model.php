<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Car_tasks_model  extends App_Model
{
    private $cartasks_columns;

    public function __construct()
    {
        parent::__construct();

        $this->cartasks_columns = hooks()->apply_filters('cartasks_columns', ['check_name_en', 'check_name_ar', 'is_check']);

    }


    public function get_carchecklist_items()
    {
    
        // var_dump($this->db->get(db_prefix() . 'car_checklist')->result_array());
        // exit();
        return $this->db->get(db_prefix() . 'car_checklist')->result_array();
    }




}