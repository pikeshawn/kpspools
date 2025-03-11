<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\TaskImage;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\CloudinaryModel;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class CloudinaryController extends Controller
{
    //
    public function upload(Request $request)
    {
//        $request->validate([
//            'image' => 'required|mimes:jpg,jpeg,png,gif|max:5120',
//        ]);



        // Check if Laravel received the file
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'No file uploaded or file not fully received'], 400);
        }

        $file = $request->file('image');

        // Ensure the file is fully received and is readable
        if (!$file->isValid()) {
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
                    "address_id" => $request->address_id,
                    "public_id" => $uploadedFile->getPublicId(),
                    "image_url" => $uploadedFile->getSecurePath()
                ]
            );

            return response()->json([
                'url' => $uploadedFile->getSecurePath(),
                'publicId' => $uploadedFile->getPublicId(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Cloudinary upload failed: ' . $e->getMessage()], 500);
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
