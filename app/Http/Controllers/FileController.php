<?php

namespace App\Http\Controllers;

use App\Models\User;

class FileController extends Controller
{
    public static $default = 'default.jpg';

    public static $diskName = 'storage';

    public static $systemTypes = [
        'profile' => ['png', 'jpg', 'jpeg'],
    ];

    private static function isValidType(string $type)
    {
        return array_key_exists($type, self::$systemTypes);
    }

    private static function defaultAsset(string $type)
    {
        return asset($type.'/'.self::$default);
    }

    private static function getFileName(string $type, int $id)
    {

        $fileName = null;
        switch ($type) {
            case 'profile':
                $fileName = User::find($id)->profile_picture_url;
                break;
            case 'post':
                // other models
                break;
        }

        return $fileName;
    }

    public static function get(string $type, int $userId)
    {

        // Validation: upload type
        if (! self::isValidType($type)) {
            return self::defaultAsset($type);
        }

        // Validation: file exists
        $fileName = self::getFileName($type, $userId);
        if ($fileName) {
            return asset($type.'/'.$fileName);
        }

        // Not found: returns default asset
        return self::defaultAsset($type);
    }
}
