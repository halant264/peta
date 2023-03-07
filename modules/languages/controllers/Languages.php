<?php

defined('BASEPATH') or exit('No direct script access allowed');
use app\services\zip\Unzip;
class Languages extends AdminController
{
    private $ci;
    public  $APP_LANG_PATH = FCPATH . 'application/language/' ;


	public function __construct()
    {
        parent::__construct();
        $this->ci = &get_instance();
    }


    /**
     * languages
     * @return view
     */
    public function lang($user_lang){
     
       
    //    $l =  $this->lang->load($user_lang  , $user_lang );
    //    $l2 = $this->lang->line('invoice_status_unpaid');
    //    $MyFile = file_get_contents(base_url()."application/controllers/readme.txt");
       $this->load->helper('file');
       $fp=file_get_contents(APPPATH . 'language/'.$user_lang.'/'.$user_lang.'_lang.php');
    //    gettype($fp) ;



        $t4 = preg_replace('(#.*[a-zA-Z].*;)', ' ' , $fp);
       
        
        $t4 = preg_replace('(#.*[a-zA-Z].*)', ' ' , $t4);
        
        $t4 = preg_replace('(#.*\s)', ' ' , $t4);
        $t4 = preg_replace('(<.*php)', ' ' , $t4);
      
        $t4 = preg_replace('/\s+/', ' ' , $t4);
        $t4 = explode(';', $t4);


        $data['title']         = $user_lang ;
        $data['content']         =  $t4 ;

        // $starts_with = " #";
        // $starts_with1 = " <";
        // // $string  = explode(" " , $string );
        // $output = preg_replace('/\s+/', ' ' , $t[1]);

        // $data['content'] = array_filter($t, function($v, $k) use ( $starts_with1 , $starts_with ) {
        // //  echo $v .'<br>' ;
        //     return (substr(preg_replace('/\s+/', ' ' , $v),0, 1) != $starts_with1  && substr(preg_replace('/\s+/', ' ' , $v),0, 2) != $starts_with ) ;
        // } ,  ARRAY_FILTER_USE_BOTH);


        $this->load->view('lang/manage', $data);
    }

    public function upload_lang()
    {
        $this->load->view('lang/upload_lang');
    }

    public function upload_languages()
    {
       
        // $this->load->library('app_lang_installer');
        $data = $this->from_upload();

        if ($data['error']) {
            set_alert('danger', $data['error']);
        } else {
            set_alert('success', 'Languages uploaded successfully');
        }

        $this->to_modules();
    }

    private function to_modules()
    {
        redirect(admin_url('/languages/upload_lang'));
    }

    public function from_upload()
    {
        if (isset($_FILES['lang']) && _perfex_upload_error($_FILES['lang']['error'])) {
            return [
                    'error'   => _perfex_upload_error($_FILES['file']['error']),
                    'success' => false,
            ];
        }
        if (isset($_FILES['lang']['name'])) {

            hooks()->do_action('pre_upload_lang', $_FILES['lang']);

            $response = ['success' => false, 'error' => ''];

            // Get the temp file path
            $uploadedTmpZipPath = $_FILES['lang']['tmp_name'];

            $unzip = new Unzip();

            $moduleTemporaryDir = get_temp_dir() . time() . '/';

            try {
                $unzip->extract($uploadedTmpZipPath, $moduleTemporaryDir);
                // if ($this->check_lang($moduleTemporaryDir) === false) {
                //     $response['error'] = 'No valid module is found.';
                // } else {
                    $unzip->extract($uploadedTmpZipPath, $this->APP_LANG_PATH);
                    hooks()->do_action('lang_installed', $_FILES['lang']);
                    $response['success'] = true;
                // }
                $this->clean_up_dir($moduleTemporaryDir);
            } catch (Exception $e) {
                $response['error'] = $e->getMessage();
            }
            return $response;
        }
    }
    private function clean_up_dir($source)
    {
        delete_files($source);
        delete_dir($source);
    }
    

    public function changeline( ){

        $key = $this->input->post('key') ;
        $lang = $this->input->post('lang');
        $value = $this->input->post('value');

        $CI =& get_instance();
        $CI->load->helper('file');
        $file_path = $this->lang_path.'/'.$lang.'/'.$lang.'_lang.php';
     
        if(!file_exists($file_path)){
            $file_path = APPPATH . 'language/'.$lang.'/'.$lang.'_lang.php';
            if(!file_exists($file_path)){
                show_error("Could not find the requested language file.");
            }

        }
        $lang_contents = read_file($file_path);

        // $rr =str_replace("\n", ";" , $r );
        $new_contents = preg_replace("^\\$"."lang\['$key'\].*\s=.*\s'(.*?)';^", '$lang'."['".$key."'] = '$value';", $lang_contents);
        
        // $new_contents = preg_replace("^\\$"."lang\['$key'\] = '(.*?)';^", '$lang'."['".$key."'] = '$value';", $r);
        // $rr = str_replace(';' , ";\n\r" , $new_contents);
        // $rr2 = str_replace('(//.*\s;)|(<?php)$' , "\n\r$0" , $rr);
        // $rr2 = str_replace('(#.*[a-zA-Z].*;)$' , "$0\n\r" , $rr2);
        // $rr2 = str_replace('(#.*[a-zA-Z])|(#.*\s)$' , "$0;\n\r" , $rr2);
        // // $rr2 = str_replace('(#.*[a-zA-Z])$' , "\n\r$0" , $rr2);
        // // $rr2 = str_replace('(#.*\s)$' , "\n\r# Version 1.0.0 # # General" , $rr2);

        write_file($file_path, $new_contents, 'w+');
  
        redirect($_SERVER['HTTP_REFERER']);
    }


}