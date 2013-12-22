<?php

class SortedSetsSelection
{
    protected $defaultLimit = 1000;
    protected $sortedSetDefaultSize = 10;
    private $cacheKey;
    private $params;
    private $_cacheComponent = null;
    /**
     * @var CDbConnection
     */
    private $_dbConnection = null;
    private $table;
    private $scoreField;
    private $selectFields = array('id');
    private $whereCondition;
    private $whereParams = array();
    private $limit = 0;
    private $offset = 0;
    private $score = '+inf';
    private $order = 'asc';
    private $recordsShift = 0;
    private $groupBy;

    public function __construct($cacheComponent)
    {
        $this->_cacheComponent = $cacheComponent;
    }

    public function setCacheKey($key)
    {
        $this->cacheKey = $key;
        return $this;
    }

    public function setDatabase($db)
    {
        if(isset($db['model'])){
            $this->_dbConnection = $db['model']->getDbConnection();
            $this->table = $db['model']->tableName();
        }
        if (isset($db['dbConnection'])) {
            $this->_dbConnection = Yii::app()->{$db['dbConnection']};
        }
        if (isset($db['table'])) {
            $this->table = $db['table'];
        }
        if (isset($db['scoreField'])) {
            $this->scoreField = $db['scoreField'];
        }
        if (isset($db['whereCondition'])) {
            $this->whereCondition = $db['whereCondition'];
        }
        if (isset($db['whereParams'])) {
            $this->whereParams = $db['whereParams'];
        }
        if (isset($db['selectFields'])) {
            $this->selectFields = $db['selectFields'];
        }
        if (isset($db['groupBy'])) {
            $this->groupBy = $db['groupBy'];
        }

        return $this;
    }

    public function setParams($params)
    {
        $this->params = $params;
        $this->limit = (isset($this->params['limit'])) ? (int)$this->params['limit'] : $this->defaultLimit;
        $this->offset = (isset($this->params['offset'])) ? (int)$this->params['offset'] : 0;
        $this->order = (isset($this->params['order'])) ? strtolower($this->params['order']) : 'desc';
        $this->score = (isset($this->params['score'])) ? $this->params['score'] : '+inf';

        return $this;
    }

    public function getRecords()
    {
        $records = $this->getIds();
        if ($this->limit == 0) {
            $this->limit = $this->defaultLimit;
            $dbParams['limit'] = $this->defaultLimit;
        }

        if (!$records) {
            $dbParams['offset'] = 0;
            if ($this->limit <= $this->sortedSetDefaultSize) {
                $dbParams['limit'] = $this->sortedSetDefaultSize + $this->offset;
            } else {
                $dbParams['limit'] = $this->limit + $this->offset;
            }
            $dbRecords = $this->findRecordsInDatabase($dbParams);
            if (!empty($dbRecords)) {
                $this->setIds($dbRecords);
                $records = $this->getIds();
            }
        }

        $this->recordsShift = $this->getRecordsShift();
        $recordsCount = count($records);
        if ($recordsCount < $this->limit) {
            $dbParams = array(
                'limit' => ($this->limit + $this->recordsShift) - $recordsCount,
                'offset' => $recordsCount + $this->recordsShift,
            );
            $dbRecords = $this->findRecordsInDatabase($dbParams);
            if (!empty($dbRecords)) {
                $this->setIds($dbRecords);
                $records = $this->getIds(false);
            }
        }
        return $records;
    }

    private function getIds($shift = true)
    {
        switch ($this->order) {
            case 'asc':
                if (($this->limit == 0) && ($this->offset == 0)) {
                    $records = $this->_cacheComponent->zRange($this->cacheKey, 0, -1);
                } else {
                    $offset = ($shift == true) ? $offset = $this->offset + $this->recordsShift : $this->offset;
                    $records = $this->_cacheComponent->zRangeByScore($this->cacheKey, '-inf', '+inf',
                        array('limit' => array($offset, $this->limit)));
                }
                break;
            case 'desc':
                if (($this->limit == 0) && ($this->offset == 0)) {
                    $records = $this->_cacheComponent->zRevRange($this->cacheKey, 0, -1);
                } else {
                    $offset = ($shift == true) ? $offset = $this->offset + $this->recordsShift : $this->offset;
                    $records = $this->_cacheComponent->zRevRangeByScore($this->cacheKey, '+inf', '-inf',
                        array('limit' => array($offset, $this->limit)));
                }
                break;
            default:
                throw new CException("order must be 'asc' or 'desc'");
        }
        $return = array();
        if (!is_array($records)) {
            throw new Exception("Failed to connect to cache");
        }
        foreach ($records as $record) {
            $return[] = unserialize($record);
        }

        return $return;
    }

    private function findRecordsInDatabase($dbParams)
    {
        $dbCommand = $this->_dbConnection->createCommand();
        $select = implode(',', $this->selectFields);
        $dbCommand->select("$select, $this->scoreField")
            ->from($this->table)
            ->order("$this->scoreField $this->order");

        if (isset($this->whereCondition)) {
            $dbCommand->where($this->whereCondition, $this->whereParams);
        }
        if (isset($this->groupBy)) {
            $dbCommand->group("$this->groupBy");
        }
        if (isset($dbParams['limit'])) {
            $dbCommand->limit = $dbParams['limit'];
        }
        if (isset($dbParams['offset'])) {
            $dbCommand->offset = $dbParams['offset'];
        }
//        debug
//        echo $dbCommand->getText();
        $dbRecords = $dbCommand->queryAll();

        $records = array();
        foreach ($dbRecords as $record) {
            $fields = array();
            foreach ($this->selectFields as $field) {
                $fields[$field] = $record[$field];
            }
            $records[] = array(
                'score' => $record[$this->scoreField],
                'fields' => $fields
            );
        }
        return $records;
    }

    private function setIds($scoreArray)
    {
        if (is_array($scoreArray)) {
            foreach ($scoreArray as $score) {
                $this->_cacheComponent->zAdd($this->cacheKey, $score['score'], serialize($score['fields']));
            }
            return true;
        } else {
            throw new CException('not array');
        }

    }

    private function getRecordsShift()
    {
        $count = 0;
        if ($this->score != '+inf') {
            switch ($this->order) {
                case 'asc':
                    $newestRecord = $this->getRecordTime('newest');
                    if ($this->score != $newestRecord) {
                        $records = $this->_cacheComponent->zRangeByScore($this->cacheKey, $newestRecord, $this->score);
                    }
                    break;
                case 'desc':
                    $oldestRecord = $this->getRecordTime('oldest');
                    if ($this->score != $oldestRecord) {
                        $records = $this->_cacheComponent->zRevRangeByScore($this->cacheKey, $oldestRecord, $this->score);
                    }
                    break;
                default:
                    throw new CException("order must be 'asc' or 'desc'");
            }
        }
        if (isset($records) && !empty($records)) {
            $count = count($records) - 1;
        }
        return $count;
    }

    private function getRecordTime($type)
    {
        switch($type){
            case 'newest':
                $record = $this->_cacheComponent->zRevRange($this->cacheKey, -1, -1, true);
                break;
            case 'oldest':
                $record = $this->_cacheComponent->zRange($this->cacheKey, -1, -1, true);
                break;
            default:
                    break;
        }

        if (!empty($record) && is_array($record)) {
            return current($record);
        }
    }
}
