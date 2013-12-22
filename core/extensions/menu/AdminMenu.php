<?php

Yii::import('bootstrap.widgets.TbNavbar');

class AdminMenu extends TbNavbar
{
    public $firstItemCssClass = 'first-item';
    public $lastItemCssClass = 'last-item';
    public $menuItems = array();

    public function init()
    {
        $this->items = array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=> $this->generateMenu($this->menuItems)
        ));
        parent::init();
    }

    private function generateMenu($menuItems)
    {
        $items = array();
        foreach ($menuItems as $value) {
            $itemsOptions = array();
            $route = isset($value['route'])?$value['route']:null;
            $url = isset($value['url'])?$value['url']:'';
            $label = isset($value['label'])?$value['label']:'';
            if($route){
                $url = Yii::app()->createUrl($route);
            }
            $currentResult = array('label' => $label, 'url' => $url, 'itemOptions' => $itemsOptions);
            if(Yii::app()->request->requestUri == $url){
                $currentResult['active'] = true;
            }
            if(isset($value['items'])){
                $currentResult['items'] = $this->generateMenu($value['items']);
            }
            if(isset($value['visible'])){
                $currentResult['visible'] = $value['visible'];
            }

            $items[] = $currentResult;
        }

        return $items;
    }

}
