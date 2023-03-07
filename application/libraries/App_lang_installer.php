<?php

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\zip\Unzip;

class App_lang_installer
{
    private $ci;

    public function __construct()
    {
        
        $this->ci = &get_instance();


    }

    /**
     * Upload module
     * @return array
     */
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

        //     var_dump($uploadedTmpZipPath);
        // exit();

            try {
                $unzip->extract($uploadedTmpZipPath, $moduleTemporaryDir);
                // if ($this->check_lang($moduleTemporaryDir) === false) {
                //     $response['error'] = 'No valid module is found.';
                // } else {
                    $unzip->extract($uploadedTmpZipPath, APP_LANGUAGES_PATH);
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

    public function check_lang($source)
    {
        // Check the folder contains at least 1 valid module.
        $modules_found = false;

     

        $files = get_dir_contents($source);

     

        if ($files) {
            foreach ($files as $file) {
                if (endsWith($file, '.php')) {
                    $info = $this->ci->app_modules->get_headers($file);
                    var_dump( $info);
                    exit();
                    if (isset($info['module_name']) && !empty($info['module_name'])) {
                        $modules_found = true;

                        break;
                    }
                }
            }
        }

        if (!$modules_found) {
            return false;
        }

        return $source;
    }

    private function clean_up_dir($source)
    {
        delete_files($source);
        delete_dir($source);
    }
}
