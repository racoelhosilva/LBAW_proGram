<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public static $default = 'default.jpg';

    public static $diskName = 'storage';

    public static $systemTypes = [
        'profile' => ['png', 'jpg', 'jpeg'],
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
            case 'post':
                // other models
                break;
        }

        return $fileName;
    }

    private static function delete(string $type, int $id)
    {
        $existingFileName = self::getFileName($type, $id);
        if ($existingFileName) {
            Storage::disk(self::$diskName)->delete($type.'/'.$existingFileName);

            switch ($type) {
                case 'profile':
                    User::find($id)->profile_image = null;
                    break;
                case 'post':
                    // other models
                    break;
            }
        }
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

    public function upload(Request $request)
    {

        // Validation: has file
        if (! $request->hasFile('file')) {
            return redirect()->back()->with('error', 'Error: File not found');
        }

        // Validation: upload type
        if (! $this->isValidType($request->type)) {
            return redirect()->back()->with('error', 'Error: Unsupported upload type');
        }

        // Validation: upload extension
        $file = $request->file('file');
        $type = $request->type;
        $extension = $file->extension();
        if (! $this->isValidExtension($type, $extension)) {
            return redirect()->back()->with('error', 'Error: Unsupported upload extension');
        }

        // Prevent existing old files
        $this->delete($type, $request->id);

        // Generate unique filename
        $fileName = $file->hashName();

        // Validation: model
        $error = null;
        switch ($request->type) {
            case 'profile':
                $user = User::findOrFail($request->id);
                if ($user) {
                    $user->profile_picture_url = $fileName;
                    $user->save();
                } else {
                    $error = 'unknown user';
                }
                break;

            case 'post':
                // other models
                break;

            default:
                redirect()->back()->with('error', 'Error: Unsupported upload object');
        }

        if ($error) {
            redirect()->back()->with('error', `Error: {$error}`);
        }

        $file->storeAs($type, $fileName, self::$diskName);

        return redirect()->back()->with('success', 'Success: upload completed!');
    }
}
