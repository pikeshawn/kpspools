<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\TaskImage;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\CloudinaryModel;
use Inertia\Inertia;

class CloudinaryController extends Controller
{
    //
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath());

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
