<?php

class CacheKey
{
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

    //Customer related:
    public static function customer($id)
    {
        return 'customer::' . $id;
    }

    public static function customerIdByPrivateEmail($email)
    {
        return 'customer::id::company_email::' . $email;
    }

    public static function customerIdByMobilePhone($phone)
    {
        return 'customer::id::mobile_phone::' . $phone;
    }

    public static function customers()
    {
        return 'customers::list';
    }

    // Webservice API Session
    public static function apiSession($sessionId)
    {
        return 'api::session::'.$sessionId;
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
}
