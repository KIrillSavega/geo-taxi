<?php

class CacheKey
{
    //Customer related:
    public static function customer($id)
    {
        return 'customer::' . $id;
    }

    public static function customerIdByEmail($email)
    {
        return 'customer::id::email::' . $email;
    }

    public static function customerIdByPhone($phone)
    {
        return 'customer::id::phone::' . $phone;
    }
    
    public static function customerTypes()
    {
        return 'customer::types';
    }

    public static function requiredCustomerFields()
    {
        return 'required_customer_fields';
    }

    //Category related:
    public static function category($id)
    {
        return 'category::' . $id;
    }

    public static function categoryName($name)
    {
        return 'category::name::' . $name;
    }

    public static function categories()
    {
        return 'categories';
    }

    public static function salesOutletCategories($salesOutlet)
    {
        return 'categories::sales_outlet::' . $salesOutlet;
    }

    //Products related:
    public static function product($id)
    {
        return 'product::' . $id;
    }

    public static function productSKU($sku)
    {
        return 'product::sku::' . $sku;
    }

    public static function productPLU($plu)
    {
        return 'product::plu::' . $plu;
    }

    public static function productUPC($upc)
    {
        return 'product::upc::' . $upc;
    }

    public static function productSKUs($id)
    {
        return 'product::'.$id.'::skus';
    }

    public static function productAlternativeSKUs($id)
    {
        return 'product::'.$id.'::alternative_skus';
    }

    public static function productsByCategory($id)
    {
        return 'product::category::'.$id;
    }

    public static function productsByBrand($id)
    {
        return 'product::brand::'.$id;
    }

    public static function productsBySalesOutlet($salesOutletId)
    {
        return 'products::sales_outlet::'.$salesOutletId;
    }

    public static function brand($id)
    {
        return 'brand::' . $id;
    }

    public static function brandName($name)
    {
        return 'brand::name::' . $name;
    }

    public static function brandsList()
    {
        return 'brands';
    }

    public static function brandsListForCategory($categoryId)
    {
        return 'brands::category::'.$categoryId;
    }

    //Modifier related:
    public static function modifier($id)
    {
        return 'modifier::'.$id;
    }

    public static function modifierGroup($id)
    {
        return 'modifier::group::'.$id;
    }

    public static function modifiersListInGroup($groupId)
    {
        return 'modifiers::group::'.$groupId;
    }

    public static function groupsListToModifier($modifierId)
    {
        return 'groups::modifier::'.$modifierId;
    }

    public static function modifierIds()
    {
        return 'modifier::ids';
    }

    public static function modifierGroupIds()
    {
        return 'modifier_group::ids';
    }

    public static function modifierGroups()
    {
        return 'modifierGroups';
    }

    //Storage related:
    public static function storageFile($id)
    {
        return 'file::'.$id;
    }

    public static function productImages($productId)
    {
        return 'product::'.$productId.'::images';
    }
    
    public static function salesOutletGalleryImages($salesOutletId)
    {
        return 'sales_outlet::'.$salesOutletId.'::gallery_images';
    }

    public static function productAttachments($productId)
    {
        return 'product::'.$productId.'::attachments';
    }

    //eShop related:
    
    public static function shopSettings($id)
    {
        return 'shop::settings::'.$id;
    }
    
    public static function shopGallery($id)
    {
        return 'shop::gallery::'.$id;
    }

    public static function themesList()
    {
        return 'themes';
    }

    //email sending related:
    public static function messageQueue()
    {
        return 'email';
    }
    //Location related:
    public static function address($id)
    {
        return 'address::' . $id;
    }

    //Quote related
    public static function quote($transactionId)
    {
        return 'quote::' . $transactionId;
    }

    public static function userQuotes($userId, $salesOutletId)
    {
        return 'quotes::user::' . $userId . '::sales_outlet::' . $salesOutletId;
    }

    public static function quoteProducts($transactionId)
    {
        return 'quote::' . $transactionId . '::products';
    }

    //Employee related:
    public static function employee($id)
    {
        return 'employee::' . $id;
    }

    public static function employeeIdByCompanyEmail($email)
    {
        return 'employee::id::company_email::' . $email;
    }

    public static function employeeIdByMobilePhone($phone)
    {
        return 'employee::id::mobile_phone::' . $phone;
    }
    
    public static function employeeIdByPinCode($pinCode)
    {
        return 'employee::id::pin_code::' . $pinCode;
    }

    public static function employeePermissionGroup($id)
    {
        return 'employee::permission_group::' . $id;
    }

    public static function employeePermissionGroups()
    {
        return 'employee::permission_groups';
    }

    public static function employees()
    {
        return 'employees::list';
    }
    
    public static function employeeIdsBySubscribeEvent( $eventId )
    {
        return 'employees_by_subscribeevent::'.$eventId;
    }
    
    public static function subscribeEventsByEmployeeId( $employeeId )
    {
        return 'subscribe_events::employee_id::'.$employeeId;
    }

    //Tax related:
    public static function tax($id)
    {
        return 'tax::' . $id;
    }

    public static function taxAgency($id)
    {
        return 'tax::agency_id' . $id;
    }

    public static function taxesIds()
    {
        return 'taxes_ids';
    }

    public static function taxCombo($id)
    {
        return 'tax::tax_combo' . $id;
    }

    public static function TaxesInComboTax($taxId)
    {
        return 'taxes_related_to_combo_tax::tax::' . $taxId;
    }

    public static function taxAgenciesIds()
    {
        return 'tax::tax_agencies_ids';
    }

    public static function taxIdByName($name)
    {
        return 'tax::id::name::' . $name;
    }

    public static function taxNameById($id)
    {
        return 'tax::name::id::' . $id;
    }

    public static function taxComboByTaxId($id)
    {
        return 'tax_combo::tax::' . $id;
    }

    public static function singleTaxes()
    {
        return 'tax::single_taxes';
    }

    // Webservice API Session
    public static function apiSession($sessionId)
    {
        return 'api::session::'.$sessionId;
    }

    // Unit of Measure related
    public static function UMGroup($id)
    {
        return 'um::group::' . $id;
    }

    public static function UMBase($id)
    {
        return 'um::base::' . $id;
    }

    public static function UMCustom($id)
    {
        return 'um::custom::' . $id;
    }

    public static function BaseUMListByTypeAndSystem($typeId, $systemId)
    {
        $listName = 'um::base::units';
        if ($typeId) {
            $listName .= '::type::' . $typeId;
        }
        if ($systemId) {
            $listName .= '::system::' . $systemId;
        }
        return $listName;
    }

    public static function CustomUMListByGroupId($groupId, $editableOnly)
    {
        $listName = 'um::custom::group::' . $groupId;
        if ($editableOnly) {
            $listName .= '::editable';
        }

        return $listName;
    }

    public static function UMGroupsList()
    {
        return 'um::groups';
    }

    // Company related
    public static function company($id)
    {
        return 'company::' . $id;
    }

    public static function companiesIds()
    {
        return 'companies_ids';
    }

    // SalesOutlet related
    public static function salesOutlet($id)
    {
        return 'sales_outlet::' . $id;
    }

    public static function eShopSalesOutletForDomain($domain)
    {
        return 'domain::'.$domain.'::sales_outlet_id';
    }

    public static function allSalesOutlets()
    {
        return 'sales_outlets';
    }

    public static function salesOutletsByType($type)
    {
        return 'sales_outlets_by_type_'.$type;
    }

    public static function salesOutletProductPrice($salesOutlet, $productId)
    {
        return 'sales_outlet::' . $salesOutlet . '::product::' . $productId . '::price';
    }

    public static function salesOutletPricedProducts($salesOutlet)
    {
        return 'sales_outlet::' . $salesOutlet . '::priced_products';
    }

    public static function salesOutletProductTax($salesOutlet, $productId)
    {
        return 'sales_outlet::' . $salesOutlet . '::product::' . $productId . '::tax';
    }

    public static function salesOutletTaxedProducts($salesOutlet)
    {
        return 'sales_outlet::' . $salesOutlet . '::taxed_products';
    }

    // Department related
    public static function department($id)
    {
        return 'department::' . $id;
    }

    // PosTerminal related
    public static function posTerminal($id)
    {
        return 'pos_terminal::' . $id;
    }

    public static function posTerminalsListBySalesOutletId($salesOutletId)
    {
        return 'pos_terminals::sales_outlet::' . $salesOutletId;
    }

    // Sales related
    public static function reason($id)
    {
        return 'sales::reason::' . $id;
    }

    public static function reasons()
    {
        return 'sales::reasons';
    }

    // Warehouse related
    public static function warehouse($id)
    {
        return 'warehouse::' . $id;
    }

    public static function product2warehouse($productId, $warehouseId, $measureUnitId)
    {
        return 'p::' . $productId . '::wh::' . $warehouseId . '::um::' . $measureUnitId;
    }

    public static function warehouseIds()
    {
        return 'warehouse_ids';
    }

    public static function productInStockByWarehouseId($productId, $warehouseId)
    {
        return 'in_stock::product::' . $productId . '::warehouse::' . $warehouseId;
    }

    public static function productStockLevel($warehouseId, $productId, $measureUnitId)
    {
        return 'warehouse::' . $warehouseId . '::product::' . $productId . '::measure_unit::'
            . $measureUnitId . '::stock_level';
    }

    // Vendor related
    public static function vendor($id)
    {
        return 'vendor::' . $id;
    }

    public static function vendorNames()
    {
        return 'vendor_names';
    }

    // PurchaseOrder related
    public static function purchaseOrder( $id )
    {
        return 'purchase_order::' . $id;
    }

    public static function product2purchaseOrder($productId)
    {
        return 'product2purchase_order::product_id::' . $productId;
    }

    // Tender related
    public static function tender( $id )
    {
        return 'tender::' . $id;
    }

    public static function tendersIds()
    {
        return 'tenders_ids';
    }

    // EmailSettings related
    public static function emailSettings( $id )
    {
        return 'email_settings::' . $id;
    }

    public static function allEmailSettings()
    {
        return 'all_email_settings';
    }

    // ElementSettings related
    public static function elementSettings( $id )
    {
        return 'element_settings::' . $id;
    }

    public static function allElementSettings()
    {
        return 'all_element_settings';
    }
    
    public static function syncTimestampForCollection($collectionId)
    {
        return 'sync_timestamp_for_collection::'.$collectionId;
    }

    // Account related
    public static function account( $id )
    {
        return 'account::' . $id;
    }

    public static function accountsIds()
    {
        return 'accounts_ids';
    }

    public static function accountsIdsByCompanyId( $companyId )
    {
        return 'company::' . $companyId . '::accounts_ids';
    }

    // Product Attributes related
    public static function attributeGroup($id)
    {
        return 'attribute_group::' . $id;
    }

    public static function allAttributeGroupsIds()
    {
        return 'all_attribute_groups';
    }

    public static function attributesByProductId($productId)
    {
        return 'attributes::product::' . $productId;
    }

    //CRM
    public static function CRMSupportCase($id)
    {
        return 'crm_support_case::' . $id;
    }

    public static function CRMSupportCasesByCustomer($customerId)
    {
        return 'crm_support_cases::customer::' . $customerId;
    }

    public static function CRMSupportCasesByContact($contactId)
    {
        return 'crm_support_cases::contact::' . $contactId;
    }

    public static function CRMContact($id)
    {
        return 'crm_contact::' . $id;
    }

    public static function CRMComment($id)
    {
        return 'crm_comment::' . $id;
    }

    public static function CRMCall($id)
    {
        return 'crm_call::' . $id;
    }

    public static function CRMClient($id)
    {
        return 'crm_client::' . $id;
    }

    public static function CRMCommentsBySupportCase($supportCaseId)
    {
        return 'crm_comments::support_case::' . $supportCaseId;
    }
}
