<?php

class QueueStorage extends CComponent
{
    /**
     * @var Redis
     */
    public $storage;

    public function __construct($storage)
    {
        $this->storage = Yii::app()->{$storage};
    }

    public function pushElementToQueue($list, $element)
    {
        return $this->storage->rPush($list, $element);
    }

    public function popElementFromQueue($list)
    {
        return $this->storage->lPop($list);
    }

    public function popElementsFromQueue($list, $numberOfElements)
    {
        $elements = $this->storage->lRange($list, 0, $numberOfElements -1);
        if ($elements) {
            $this->storage->lTrim($list, count($elements), -1);
        }
        return $elements;
    }

}