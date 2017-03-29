<?php

/**
 * Class SaloonEntity
 */
abstract class SaloonEntity implements SaloonEntityInterface {

    /**
     * @var array
     */
    protected $mandatoryFields = ['created_by', 'created_on', 'modified_by', 'modified_on', 'id_firme'];

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @param $tableName
     */
    public function __construct($tableName) {
        $this->tableName = $tableName;
    }

    /**
     * @return mixed
     */
    public function getAll() {
        db_set_active(CompanyDatabase::resolveDatabaseName(getUserFirma()));
        $result = db_query("select * from {$this->tableName} ")->fetchAll();
        db_set_active();
        return $result;
    }

    /**
     * @param $colName
     * @return mixed
     */
    public function getCol($colName) {
        db_set_active(CompanyDatabase::resolveDatabaseName(getUserFirma()));
        $result = db_query("select {$colName} from {$this->tableName} ")->fetchCol();
        db_set_active();
        return $result;
    }

    /**
     * @param $colName
     * @return array
     */
    public function getColWithIds($colName) {
        db_set_active(CompanyDatabase::resolveDatabaseName(getUserFirma()));
        $queryResults = db_query("select id, {$colName} from {$this->tableName} ")->fetchAll();
        db_set_active();
        $results = [];
        foreach($queryResults as $result) {
            $results[$result->id] = $result->$colName;
        }
        return $results;
    }

    /**
     * @param $colNames
     * @param $condition
     * @return array
     */
    public function getColsWithIdsWithCondition(array $colNames, $condition) {
        if (count($colNames) == 0) {
            $select = '*';
        } else {
            $select = implode(', ', $colNames);
        }
        db_set_active(CompanyDatabase::resolveDatabaseName(getUserFirma()));
        $queryResults = db_query("select {$select} from {$this->tableName} WHERE {$condition}")->fetchAll();
        db_set_active();
        return $queryResults;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id) {
        db_set_active(CompanyDatabase::resolveDatabaseName(getUserFirma()));
        $result = db_query("select * from {$this->tableName} WHERE id = :id", array(':id' => $id))->fetchAll();
        db_set_active();
        return isset($result) && is_array($result) ? reset($result) : false;
    }

    /**
     * @param array $data
     * @param null $id
     * @return int|null
     * @throws Exception
     */
    public function save(array $data, $id = null) {
        try {
            $this->validate($data);
            $this->prepare($data, $id);
            if ($id) {
                $this->update($data, $id);
            } else {
                $id = $this->insert($data);
            }
        } catch (Exception $ex) {
            watchdog('widget', $ex, array(), WATCHDOG_ERROR);
            drupal_set_message('An error has occurred.', 'error');
        }
        return $id;
    }

    public function delete($id) {
        try {
            db_set_active(CompanyDatabase::resolveDatabaseName(getUserFirma()));
            $num_deleted = db_delete($this->tableName)->condition('id', $id)->execute();
            db_set_active();
        } catch (Exception $ex) {
            $num_deleted = 0;
            watchdog('widget', $ex->getMessage(), WATCHDOG_ERROR);
            drupal_set_message('An error has occurred.', 'error');
        }
        return (bool)$num_deleted;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    abstract protected function validate($data);

    /**
     * @param array $data
     * @param null $id
     * @return mixed
     */
    abstract protected function additionalPrepare(array &$data, $id = null);

    /**
     * @param $data
     * @return int
     * @throws Exception
     */
    private function insert($data) {
        db_set_active(CompanyDatabase::resolveDatabaseName(getUserFirma()));
        $result = (int)db_insert($this->tableName)->fields($data)->execute();
        db_set_active();
        return $result;
    }

    /**
     * @param $data
     * @param $id
     * @return bool
     */
    private function update($data, $id) {
        db_set_active(CompanyDatabase::resolveDatabaseName(getUserFirma()));
        $result = (bool)db_update($this->tableName)->fields($data)->condition('id', $id)->execute();
        db_set_active();
        return $result;
    }

    /**
     * @param array $data
     * @param null $id
     * @return mixed
     */
    private function prepare(array &$data, $id = null) {
        global $user;
        foreach($data as $fieldName => $fieldValue) {
            if (empty($fieldValue)) {
                unset($data[$fieldName]);
            }
        }
        unset($data['submit'], $data['form_build_id'], $data['form_token'], $data['form_id'], $data['op'], $data['id']);
        if ($id) {
            $data['modified_on'] = DateAndTime::now()->toString();
            $data['modified_by'] = $user->uid;
        } else {
            $data['created_on'] = DateAndTime::now()->toString();
            $data['created_by'] = $user->uid;
        }
        $this->additionalPrepare($data, $id);
    }

}