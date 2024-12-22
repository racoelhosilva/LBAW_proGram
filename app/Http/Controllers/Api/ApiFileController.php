<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApiFileController extends Controller
{
    public static $default = 'default.jpg';

    public static $diskName = 'storage';

    public static $systemTypes = [
        'profile' => ['png', 'jpg', 'jpeg'],
        'banner' => ['png', 'jpg', 'jpeg'],
        'temporary' => ['png', 'jpg', 'jpeg', 'mp4'],
    ];

    private static function getDefaultExtension(string $type)
    {
        return reset(self::$systemTypes[$type]);
    }

    private static function isValidExtension(string $type, string $extension)
    {
        $allowedExtensions = self::$systemTypes[$type];

        return in_array(strtolower($extension), $allowedExtensions);
    }

    private static function isValidType(string $type)
    {
        return array_key_exists($type, self::$systemTypes);
    }

    private static function defaultAsset(string $type): string
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
            case 'banner':
                $fileName = User::find($id)->banner_image_url;
                break;
        }

        return $fileName;
    }

    private static function deleteFile(Request $request)
    {
        $url = explode('/', $request->url);
        $fileName = end($url);
        $folder = $url[count($url) - 2];
        Storage::disk(self::$diskName)->delete($folder.'/'.$fileName);

        return response()->json([
            'message' => 'File deleted successfully',
        ]);
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
            if (Storage::disk('storage')->exists($type.'/'.$fileName)) {
                return asset($type.'/'.$fileName);
            }
        }

        // Not found: returns default asset
        return self::defaultAsset($type);
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'user_id' => 'required|integer',
            'file' => 'required|file',
        ]);

        // Validation: has file
        if (! $request->hasFile('file')) {
            return response()->json([
                'error' => 'File not found',
            ], 409);
        }

        // Validation: upload extension
        $file = $request->file('file');
        $type = $request->input('type');
        $user_id = $request->input('user_id');
        $extension = $file->extension();

        if (! $this->isValidExtension($type, $extension)) {
            return response()->json([
                'error' => 'Extension not found',
            ], 422);
        }

        $fileName = $file->hashName();

        $file->storeAs($type.'/'.$user_id, $fileName, self::$diskName);

        return response()->json([
            'url' => asset($type.'/'.$user_id.'/'.$fileName),
        ]);
    }

    public static function updateImage(string $type, int $id, UploadedFile $file)
    {
        if (! $file) {
            return 'File not found';
        }

        if (! self::isValidType($type)) {
            return 'Type not found';
        }
        $extension = $file->extension();
        if (! self::isValidExtension($type, $extension)) {
            return 'Extension not found';
        }

        self::delete($type, $id);

        $fileName = $file->hashName();

        $user = User::findOrFail($id);
        if (! $user) {
            return 'User not found';
        }

        switch ($type) {
            case 'profile':
                $user->profile_picture_url = $fileName;
                break;
            case 'banner':
                $user->banner_image_url = $fileName;
                break;
            default:
                return 'Type not found';
        }

        $user->save();

        $file->storeAs($type, $fileName, self::$diskName);

        return 'success';
    }
}
