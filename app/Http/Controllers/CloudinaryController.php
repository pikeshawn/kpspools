<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CloudinaryModel;
use App\Models\Customer;
use App\Models\TaskImage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CloudinaryController extends Controller
{
    //
    public function upload(Request $request)
    {
        //        $request->validate([
        //            'image' => 'required|mimes:jpg,jpeg,png,gif|max:5120',
        //        ]);

        $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $request->file('image')->getRealPath());
        Log::info('Detected MIME Type: '.$mimeType);

        Log::info('Upload request received', [
            'content_length' => $_SERVER['CONTENT_LENGTH'] ?? 'not set',
            'request_size' => strlen(file_get_contents('php://input')),
            'raw_input' => file_get_contents('php://input'),
        ]);

        //        Log::debug(mime_content_type('path/to/test-image.jpg'));

        // Debugging: Check the file data
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            Log::info('File detected', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'client_mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'error_code' => $file->getError(),
                'real_path' => $file->getRealPath(),
            ]);
        } else {
            Log::error('No file uploaded. Request Data:', $request->all());

            return response()->json(['error' => 'No file uploaded or file not fully received'], 400);
        }

        //        dd($request);

        // Check if Laravel received the file
        //        if (!$request->hasFile('image')) {
        //            Log::error("No file uploaded.");
        //            return response()->json(['error' => 'No file uploaded or file not fully received'], 400);
        //        }

        $file = $request->file('image');

        // Ensure the file is fully received and is readable
        if (! $file->isValid()) {
            Log::error('File upload incomplete or corrupted.');

            return response()->json(['error' => 'File upload incomplete or corrupted'], 400);
        }

        Log::info('File upload request received', [
            'file_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        // Upload to Cloudinary
        try {
            $uploadedFile = Cloudinary::upload($file->getRealPath());

            CloudinaryModel::firstOrCreate(
                [
                    'address_id' => $request->address_id,
                    'public_id' => $uploadedFile->getPublicId(),
                    'image_url' => $uploadedFile->getSecurePath(),
                ]
            );

            return response()->json([
                'url' => $uploadedFile->getSecurePath(),
                'publicId' => $uploadedFile->getPublicId(),
                'name' => $uploadedFile->getOriginalFileName(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Cloudinary upload failed: '.$e->getMessage()], 500);
        }

        //        $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath());

    }

    public function images($addressId)
    {
        $images = CloudinaryModel::where('address_id', $addressId)->get();
        $address = Address::find($addressId);
        $customer = Customer::find($address->customer_id);

        return Inertia::render('Customers/GalleryPage', [
            'images' => $images,
            'address' => $address,
            'customer' => $customer,
        ]);

    }

    public function getImage()
    {
        $imageUrl = Cloudinary::getImageTag('public_id')->toHtml();
    }

    public function resizeImage()
    {
        $imageUrl = Cloudinary::getImageTag('public_id', ['width' => 300, 'height' => 300, 'crop' => 'fill'])->toHtml();
    }

    public function deleteImage($publicId)
    {
        Cloudinary::destroy($publicId);

        $image = CloudinaryModel::where('public_id', $publicId)->first();
        if ($image) {
            $image->delete();
        }

        $taskImages = TaskImage::where('public_id', $publicId)->get();

        foreach ($taskImages as $taskImage) {
            $taskImage->delete();
        }

        return response()->json(['message' => 'Image deleted successfully']);

    }
}
