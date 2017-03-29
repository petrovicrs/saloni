<?php

class CompanyCreator {

    /**
     * @var
     */
    private $database_conf;

    /**
     * @var
     */
    private $company_name;

    /**
     * @var
     */
    private $company_id;

    /**
     * @param $company_name
     * @param $company_id
     */
    public function __construct($company_name, $company_id) {
        $this->company_name = $company_name;
        $this->company_id = $company_id;
    }


    public function create() {
        $this->addToConfFile();
        $this->createDatabase();
    }

    private function addToConfFile() {
        $conf_file = conf_path() . '/settings.php';
        include $conf_file;
        $dbName = 'salon_firma_' . $this->company_id;
        if (isset($databases)) {
            if (!in_array($dbName, $databases)) {
                $database = array(
                    'driver' => 'mysql',
                    'database' => $dbName,
                    'username' => isset($databases['default']['default']['username']) ? $databases['default']['default']['username'] : 'root',
                    'password' => isset($databases['default']['default']['password']) ? $databases['default']['default']['password'] : 'root',
                    'host' => 'localhost',
                    'prefix' => '',
                    'company_name' => $this->company_name,
                    'port' => '',
                );
                $this->database_conf = $database;
                file_put_contents($conf_file, "\n" . '$databases["'.$dbName.'"]["default"] = ' . var_export($database, TRUE) . ";", FILE_APPEND);
            }
        } else {
            throw new Exception('missing conf file!');
        }
    }

    private function createDatabase() {
        $db_name = $this->database_conf['database'];
        Database::addConnectionInfo($db_name, 'default', $this->database_conf);
        try {
            db_query('CREATE DATABASE IF NOT EXISTS `'.$db_name.'`');
            $sql = file_get_contents(drupal_get_path('module', 'saloni_extra') . '/lib/prepare/saloni.sql');
            $statements = explode("--", $sql);
            db_set_active($db_name);
            foreach($statements as $statement) {
                db_query($statement);
            }
            db_set_active();
            drupal_set_message('You have successfully saved new company !');
        } catch(Exception $e) {
            drupal_set_message('An error has occurred!', 'error');
            watchdog_exception('error', $e);
        }
    }

}