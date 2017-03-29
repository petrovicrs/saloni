<?php

class CompanyDatabase {

    /**
     * @param $companyId
     * @return string
     */
    public static function resolveDatabaseName($companyId) {
        return 'salon_firma_' . $companyId;
    }

}