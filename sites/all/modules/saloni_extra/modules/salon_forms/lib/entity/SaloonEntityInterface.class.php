<?php

/**
 * Interface SaloonEntityInterface
 */
interface SaloonEntityInterface {

    /**
     * @return mixed
     */
    public function getAll();


    /**
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * @param array $data
     * @param null $id
     * @return mixed
     */
    public function save(array $data, $id = null);

}