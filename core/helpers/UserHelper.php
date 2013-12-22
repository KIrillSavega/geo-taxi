<?php
/**
 * Created by Anton Logvinenko.
 * email: a.logvinenko@mobidev.biz
 * Date: 3/22/13
 * Time: 3:17 PM
 */

class UserHelper
{
    const salt = 'ololosha13!';

    public static function hashPassword($password)
    {
        return hash_hmac('sha256', $password, self::salt);
    }

    public static function generatePassword($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}