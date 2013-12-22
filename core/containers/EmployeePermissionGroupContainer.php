<?php

class EmployeePermissionGroupContainer 
{
    public $id;
    public $title;
    
    public $viewParentCompany;
    public $updateParentCompanySettings;

    public $viewCompanyList;
    public $viewCompanyDetails;
    public $createCompany;
    public $updateCompany;
    public $deleteCompany;
    public $deleteEmailTemplate;

    public $viewSalesOutletList;
    public $viewSalesOutletDetails;
    public $createSalesOutlet;
    public $updateSalesOutlet;
    public $deleteSalesOutlet;

    public $viewDepartmentList;
    public $viewDepartmentDetails;
    public $createDepartment;
    public $updateDepartment;
    public $deleteDepartment;
    public $updateDepartmentCategory;
    public $deleteDepartmentCategory;

    public $viewProductList;
    public $viewProductDetails;
    public $createProduct;
    public $updateProduct;
    public $deleteProduct;
    public $updateProductSalesOutletPrice;
    public $updateProductSalesOutletTax;

    public $viewCategoryList;
    public $createCategory;
    public $updateCategory;
    public $deleteCategory;

    public $viewBrandList;
    public $viewBrandDetails;
    public $createBrand;
    public $updateBrand;
    public $deleteBrand;

    public $viewTaxList;
    public $viewTaxDetails;
    public $createTax;
    public $updateTax;
    public $deleteTax;

    public $viewEmployeeList;
    public $viewEmployeeDetails;
    public $createEmployee;
    public $updateEmployee;
    public $deleteEmployee;
    public $editEmployeeSubscribitions;
    public $updateWorkTime;

    public $viewPermissionGroupList;
    public $viewPermissionGroupDetails;
    public $updatePermissionGroup;
    public $deletePermissionGroup;

    public $viewCustomerList;
    public $viewCustomerDetails;
    public $createCustomer;
    public $updateCustomer;
    public $deleteCustomer;
    public $updateCustomerBalance;

    public $createAddress;
    public $updateAddress;

    public $updateEshopSettings;
    public $viewEshopPages;
    public $createEshopPage;
    public $updateEshopPage;
    public $deleteEshopPage;
    public $viewEshopMenu;
    public $createEshopMenuItem;
    public $updateEshopMenuItem;
    public $updateEshopMenuOrder;
    public $deleteEshopMenuItem;

    public $viewMeasureList;
    public $createMeasureGroup;
    public $updateMeasureGroup;
    public $createMeasureCustomUnit;
    public $updateMeasureCustomUnit;
    public $deleteMeasureGroup;
    public $deleteMeasureCustomUnit;
    public $viewUnitOfMeasure;

    public $viewEshopThemeList;
    public $viewEshopThemeDetails;
    public $createEshopTheme;
    public $updateEshopTheme;
    public $deleteEshopTheme;

    public $viewSaleList;
    public $viewSaleDetails;
    public $completeSale;
    public $voidSale;
    public $shipSale;
    public $cancelSale;
    public $reserveSale;
    public $returnSaleInAdmin;

    public $viewQuoteList;
    public $viewQuoteDetails;
    public $editQuote;
    public $deleteQuote;

    public $viewModifierList;
    public $viewModifierDetails;
    public $createModifier;
    public $updateModifier;
    public $deleteModifier;

    public $viewGalleryDetails;
    public $updateGallery;

    public $viewPosTerminalList;
    public $viewPosTerminalDetails;
    public $createPosTerminal;
    public $updatePosTerminal;
    public $deletePosTerminal;

    public $viewLossPreventionList;
    public $viewLossPreventionDetails;
    public $createLossPreventionReason;
    public $deleteLossPreventionReason;
    public $updateLossPreventionReason;

    public $viewStockLocationsList;
    public $createStockLocation;
    public $updateStockLocation;
    public $viewStockLocation;
    public $deleteStockLocation;

    public $createStockLocationAddress;
    public $updateStockLocationAddress;

    public $viewStockLocationsProducts;
    public $addStockLocationProducts;
    public $setStockLocationProducts;
    public $transferStockLocationProducts;
    public $convertStockLocationProducts;
    public $deleteStockLocationProductRecord;

    public $viewStockLocationsActivitiesList;
    public $viewStockLocationActivityDetails;
    public $updateStockLocationActivityDetails;

    public $viewProductStockLevelsList;
    public $viewProductStockLevelsDetails;
    public $createProductStockLevels;
    public $updateProductStockLevels;
    public $deleteProductStockLevels;

    public $viewVendorList;
    public $updateVendor;
    public $createVendor;
    public $viewVendorDetails;
    public $deleteVendor;

    public $createVendorBillingAddress;
    public $updateVendorBillingAddress;

    public $viewTenderList;
    public $viewTenderDetails;
    public $createTender;
    public $updateTender;
    public $deleteTender;

    public $searchProductsByTitle;
    public $searchProductByKey;
    public $getMeasureUnitsListByProductId;
    public $getProductVendorInfo;

    public $viewPurchaseOrdersList;
    public $viewPurchaseOrderDetails;
    public $createPurchaseOrder;
    public $updatePurchaseOrder;
    public $declinePurchaseOrder;
    public $acceptPurchaseOrder;
    public $viewPurchaseOrderReports;
    public $viewPurchaseOrderBarcodes;
    public $acceptVendorInvoiceForPurchaseOrder;

    public $addVendorForProduct;
    public $updateVendorForProduct;
    public $deleteVendorForProduct;
    public $viewVendorForProductDetails;

    public $viewReportsList;
    public $generateReports;

    public $viewCreditRecordDetails;

    public $viewAccountList;
    public $viewAccountDetails;
    public $createAccount;
    public $updateAccount;
    public $deleteAccount;
    public $setProductPriceOnPos;

    public $viewAttributeGroupsList;
    public $viewAttributeGroup;
    public $createAttributeGroup;
    public $updateAttributeGroup;
    public $deleteAttributeGroup;

    public $viewUndepositedFundsList;
    public $viewUndepositedFundsDetails;
    public $createUndepositedFunds;

    public $viewVendorBillsList;
    public $createVendorBill;
    public $updateVendorBill;
    public $viewVendorBillDetails;
    public $deleteVendorBill;

    public $viewMetaTags;
    public $createMetaTag;
    public $removeMetaTag;

    public $viewProductsGallery;
    public $addProductToProductsGallery;
    public $removeProductFromProductsGallery;

    public $viewSupportCasesList;
    public $viewSupportCaseDetails;
    public $createSupportCase;
    public $updateSupportCase;
    public $deleteSupportCase;

    public $viewContactsList;
    public $viewContactDetails;
    public $createContact;
    public $updateContact;
    public $deleteContact;

    public $viewCallsList;
    public $viewCallDetails;
    public $createCall;
    public $updateCall;
    public $deleteCall;

    public $viewCheckPaymentsList;
    public $createCheckPayment;
    public $updateCheckPayment;
    public $viewCheckPaymentDetails;
    public $deleteCheckPayment;

    public $viewComments;
    public $createComment;
    public $updateComment;
    public $deleteComment;

    public $updateCustomerRequiredFields;
}