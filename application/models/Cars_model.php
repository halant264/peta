<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Cars_model extends App_Model
{
    private $car_columns;

    public function __construct()
    {
        parent::__construct();

        $this->car_columns = hooks()->apply_filters('car_columns', ['id_customer', 'car_name', 'id_brand', 'id_model' ,'model_year', 'plate_source', 'plate_code', 'plate_number']);

        // $this->load->model(['client_vault_entries_model', 'client_groups_model', 'statement_model']);
       
    }

     /**
     * Add new contact
     * @param array  $data               $_POST data
     * @param mixed  $customer_id        customer id
     * @param boolean $not_manual_request is manual from admin area customer profile or register, convert to lead
     */
    public function add_contact($data, $customer_id, $not_manual_request = false)
    {

        $insert = array(
            'id_customer' => $customer_id  , 
            'car_name' => $data['car_name']  , 
            'id_brand' => $data['brand']  , 
            'id_model' => $data['model'] , 
            'model_year' => $data['model_year']  , 
            'plate_source' => $data['plate_source']  , 
            'plate_code' => $data['plate_code']  , 
            'plate_number' =>  $data['plate_number'], 
        ) ;


        $this->db->insert(db_prefix() .'cars', $insert);

        $car_id = $this->db->insert_id();

        return $car_id ;

    }
    public function get_cars($id)
    {
        $data =  $this->db->where(db_prefix() .'cars.id_customer', $id);
        $data = $this->db->get(db_prefix() . 'cars')->result_array();

        return $data ;

    }
    public function get_customer_car($id)
    {
        $data =  $this->db->where(db_prefix() .'cars.id', $id);
        $data = $this->db->get(db_prefix() . 'cars')->row();
        
        return $data ;

    }

    public function delete_car($id)
    {

        $this->db->where('id', $id);
        $this -> db -> delete(db_prefix() . 'cars');

    }

    public function get_car($id)
    {
        // $data =  $this->db->where(db_prefix() .'cars.id', $id);
        // $data = $this->db->get(db_prefix() . 'cars')->row();
        $data['model'] = '' ; 
        $car = $this->db->select( db_prefix() .'cars.id as id ,' 
         .db_prefix() .'cars.id_customer as id_customer ,' 
         .db_prefix() .'cars.car_name as car_name ,' 
         .db_prefix() .'cars.id_brand as id_brand ,'  
         .db_prefix() .'cars.id_model as id_model ,' 
         .db_prefix() .'cars.model_year as model_year ,' 
         .db_prefix() .'cars.plate_source as plate_source ,'
         .db_prefix() .'cars.plate_code as plate_code ,' 
         .db_prefix() .'cars.plate_number as plate_number ,' 
         .db_prefix() .'brand.brand_name as brand_name ,'
         .db_prefix() .'brand_model.model_name as model_name ,'
         )
        ->from(db_prefix() . 'cars')
        ->where( db_prefix() .'cars.id',$id)
        ->join( db_prefix().'brand', db_prefix().'brand.id =' .db_prefix().'cars.id_brand')
        ->join( db_prefix().'brand_model', db_prefix().'brand.id =' .db_prefix().'brand_model.id_brand')
        ->get()->row();

        if($car->id_brand !=''){
            $model = $this->db->select('*')
            ->from(db_prefix() .'brand_model')
            ->where( db_prefix() .'brand_model.id_brand', $car->id_brand)
            ->get()->result_array();
            $data['model'] = $model ; 
        }
        $data['car'] = $car; 
        
        return $data ;

    }

    public function update($data, $id)
    {
        $insert = array(
            'car_name' => $data['car_name']  , 
            'id_brand' => $data['brand']  , 
            'id_model' => $data['model'] , 
            'model_year' => $data['model_year']  , 
            'plate_source' => $data['plate_source']  , 
            'plate_code' => $data['plate_code']  , 
            'plate_number' =>  $data['plate_number'], 
        ) ;

        $this->db->where('id', $id);
        $this->db->update(db_prefix().'cars', $insert);
        if ($this->db->affected_rows() > 0) {

           

            log_activity('Car Updated [' . $data['name'] . ', ID:' . $id . ']');

            return true;
        }

        return false;
    }


}