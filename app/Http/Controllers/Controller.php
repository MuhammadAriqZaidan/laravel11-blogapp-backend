<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function saveImage($image, $path = 'public')
    {
        // Validasi jika image tidak diberikan
        if (!$image) {
            return null;
        }

        // Menghapus prefix base64 (data:image/png;base64,)
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            $image = substr($image, strpos($image, ',') + 1);
            $type = strtolower($type[1]); // Mendapatkan tipe gambar (png, jpg, dll)

            // Validasi tipe gambar
            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                return response(['message' => 'Invalid image type.'], 400);
            }

            // Decode gambar dari base64
            $decodedImage = base64_decode($image);

            // Buat nama file unik
            $filename = time() . '.' . $type;

            // Simpan gambar ke path yang diinginkan
            Storage::disk($path)->put($filename, $decodedImage);

            // Return URL gambar yang disimpan
            return URL::to('/') . '/storage/' . $path . '/' . $filename;
        } else {
            return response(['message' => 'Invalid base64 image data.'], 400);
        }
    }
}
