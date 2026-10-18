<?php
/**
 * Created by PhpStorm.
 * User: den
 * Date: 02.01.2019
 * Time: 17:56
 */

namespace App\DataFeedApp;


use Symfony\Component\Yaml\Yaml;

class DataFeedFieldManager
{

    const FIELDS_INFO_FILE = __DIR__ . '/../../config/packages/feed/data_feed_fields.yml';

    /** data-feed-fields.yml
     * @var array
     */
    protected $fieldsInfo = [];


    /**USE
     * Array of field for columns settings form without group
     * @return array
     */
    public function getFormFieldChoices()
    {
        $rows = [];
        foreach ($this->getFieldsInfo() as $fieldId => $info) {

            if( in_array($info['filterType'],[1,2])){
                $rows[] =  [$fieldId => $fieldId];
            }

        }
        return $rows;

    }

    /**USE
     * @return array
     */
    public function getFieldValueTypes()
    {
        $rows = [];
        foreach ($this->getFieldsInfo() as $fieldId => $info) {
            $rows[$fieldId] =  $info['valueType'];
        }
        return $rows;

    }


    public function getFieldsInfo()
    {
        if($this->fieldsInfo == null) {
            $this->fieldsInfo = $this->getDataFromFile($this::FIELDS_INFO_FILE);
        }
        return  $this->fieldsInfo;
    }



    /**
     * @param string $fileName
     * @return array
     * @throws \LogicException
     */
    protected function getDataFromFile($fileName)
    {
        if (!$this->isFileAvailable($fileName)) {
            throw new \LogicException('File ' . $fileName . ' is not available');
        }

        $fileName = realpath($fileName);

        return Yaml::parse(file_get_contents($fileName));
    }

    /**
     * @param string $fileName
     * @return bool
     */
    protected function isFileAvailable($fileName)
    {
        return is_file($fileName) && is_readable($fileName);
    }
}
