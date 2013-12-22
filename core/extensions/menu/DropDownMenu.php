<?php
/**
 * Created by Anton Logvinenko.
 * email: a.logvinenko@mobidev.biz
 * Date: 4/19/13
 * Time: 1:34 PM
 */

Yii::import('zii.widgets.CMenu');

class DropDownMenu extends CMenu
{
    public $firstItemCssClass = 'first-item';
    public $lastItemCssClass = 'last-item';
    public $menuType = 'navigation';

    public function init()
    {
        $this->items = $this->{'generate'.ucfirst($this->menuType).'Menu'}();
        parent::init();
    }

    private function generateBrandsMenu()
    {
        $categoryName = Yii::app()->request->getQuery('name');
        if($categoryName){
            $category = Yii::app()->category->getByName($categoryName);
        }else{
            $currentSalesOutlet = Yii::app()->salesOutlet->getCurrentSalesOutlet();
            $categories = Yii::app()->category->getCategoriesBySalesOutletId($currentSalesOutlet->id);
            if(isset($categories[1])){
                $category = $categories[1];
            }
        }

        $items = array();
        if(isset($category)){
            $brands = Yii::app()->brand->getAllRelatedToCategory($category->id);
            foreach($brands as $brand){
                $node = array();
                $node['label'] = $brand->title;
                $node['itemOptions'] = array('data-brand-id' =>$brand->id);

                $items[] = $node;
            }
        }

        return $items;
    }

    private function generateCategoriesMenu()
    {
        $currentSalesOutlet = Yii::app()->salesOutlet->getCurrentSalesOutlet();
        $categories = Yii::app()->category->getCategoriesBySalesOutletId($currentSalesOutlet->id);
        $elements = array();
        foreach($categories as $category){
            $node = array();
            $node['label'] = $category->title;
            $node['name'] = $category->name;
            $menuItemUrl = Yii::app()->createUrl('catalog/category', array('name' => $category->name));
            $node['id'] = $category->id;
            $node['parentId'] = $category->parentId;
            $node['level'] = $category->level;
            $node['url'] = array('catalog/category', 'name' => $category->name);
            $node['itemOptions'] = array('data-name' => $node['name']);
            if(Yii::app()->request->requestUri == $menuItemUrl){
                $node['itemOptions']['class'] = 'current';
            }
            $elements[] = $node;
        }

        function createTree(&$list, $parent){
            $tree = array();
            foreach ($parent as $key => $node) {
                if (isset($list[$node['id']])) {
                    $items = createTree($list, $list[$node['id']]);
                    $node['itemOptions'] = array();
                    if(!empty($items)){
                        $node['itemOptions'] = array('class' =>'children');
                    }
                    $node['itemOptions']['data-name'] = $node['name'];

                    $node['items'] = $items;
                }
                $tree[] = $node;
            }
            return $tree;
        }

        $new = array();
        if ($elements) {
            foreach ($elements as $element) {
                $new[$element['parentId']][] = $element;
            }
            $result = createTree($new,  array($elements[0]));
            if(isset($result[0]['items'])){
                return $result[0]['items'];
            }

        }

        return $new;
    }

    private function generateNavigationMenu($itemId = 0)
    {
        $items = array();
        $itemsArray = $this->getNavigationItemsByParentId($itemId);

        foreach ($itemsArray as $value) {
            $itemsOptions = array();
            $id = $value['id'];
            $pageUrl = $value['url'];
            $route = $value['route'];
            $pageId = $value['pageId'];
            $label = $value['label'];

            $url = null;
            if($route){
                $url = Yii::app()->createUrl($route);
            }else if ($pageId) {
                $page = Yii::app()->eshop->getPageById($pageId);
                $url = Yii::app()->createUrl('site/page', array('name' => $page->name));
            } else if ($pageUrl) {
                $url = $pageUrl;
            }

            $subItems = null;
            if (Yii::app()->eshop->hasChildMenuItems($id) == true) {
                $subItems = $this->generateNavigationMenu($id);
                $itemsOptions = array('class' => 'children');
            }

            if (isset($url)) {
                if(Yii::app()->request->requestUri == $url){
                    if (isset($itemsOptions['class'])){
                        $itemsOptions['class'] .= ' active';
                    }else{
                        $itemsOptions['class'] = 'active';
                    }
                }
                $currentResult = array('label' => ucwords($label), 'url' => $url, 'id' => $id, 'itemOptions' => $itemsOptions);
            } else {
                $currentResult = array('label' => ucwords($label), 'id' => $id, 'itemOptions' => $itemsOptions);
            }

            if (is_array($subItems)) {
                $currentResult = array_merge($currentResult, array('items' => $subItems));
            }
            $items[] = $currentResult;
        }

        return $items;
    }

    private function getNavigationItemsByParentId($parentId = 0)
    {
        $itemsArray = array();
        $salesOutlet = Yii::app()->salesOutlet->getCurrentSalesOutlet();
        $items = Yii::app()->eshop->getMenuItemsByParentId($parentId, $salesOutlet->id);

        if (!empty($items)) {
            foreach ($items as $item) {
                $itemsArray[] = array(
                    'label' => $item->title,
                    'url' => $item->url,
                    'pageId' => $item->pageId,
                    'route' => $item->route,
                    'id' => $item->id,
                );
            }
        }

        return $itemsArray;
    }
}
