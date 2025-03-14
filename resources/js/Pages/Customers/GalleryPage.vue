<template>
    <layout
        title="Customers"
        :user="user"
        :addressId="address.id"
    >
        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">{{ customer.first_name }} {{ customer.last_name }} Image Gallery</h1>

            <!-- Image Upload Component -->
            <div class="flex justify-center mb-6">
                <ImageUpload :address-id="address.id" @imageUploaded="addImage" />
            </div>

            <!-- Image Grid -->
            <div v-if="imageList.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <div v-for="(image, index) in imageList" :key="index" class="relative group">
                    <img :src="image.url" class="w-full h-48 object-cover rounded-lg shadow-md" alt="Uploaded Image" />
                    <!-- Delete Button -->
                    <button
                        @click="showDeleteModal(image.public_id)"
                        class="absolute top-2 right-2 bg-red-600 text-black p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                        X
                    </button>
                    <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                            <h2 class="text-lg font-semibold text-gray-900">Are you sure?</h2>
                            <p class="text-gray-600 mt-2">Do you really want to delete this image? This action cannot be undone.</p>
                            <div class="flex justify-end mt-4 space-x-2">
                                <button
                                    @click="isModalOpen = false"
                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
                                >
                                    Cancel
                                </button>
                                <button
                                    @click="deleteImage(image.public_id, index)"
                                    class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Images Message -->
            <p v-else class="text-center text-gray-500 mt-6">No images available. Upload one to get started.</p>
        </div>

    </layout>
</template>

<script>




</script>

<script setup>
import { ref, defineProps } from "vue";
import JetInput from '@/Jetstream/Input'
import SimpleTable from "../Shared/SimpleTable";
import Layout from "../Shared/Layout";
import {Link} from '@inertiajs/inertia-vue3';
import {Inertia} from '@inertiajs/inertia';
import axios from "axios";
import ImageUpload from "../Components/ImageUpload.vue";// Import the upload component

// Props: Initial images from Cloudinary
const props = defineProps({
    images: Array,  // List of Cloudinary public IDs
    address: Object,
    customer: Object,
    user: Object
});

const isModalOpen = ref(false);
const imageToDelete = ref(null);

const showDeleteModal = (imageName) => {
    imageToDelete.value = imageName;
    isModalOpen.value = true;
};

// Reactive state to track images
const imageList = ref(props.images.map((id) => ({
    public_id: id.public_id,
    url: id.image_url
})));

// Function to add a new image after uploading
const addImage = (publicId) => {
    const newImageUrl = `https://res.cloudinary.com/jemmson-inc/image/upload/${publicId}`;
    imageList.value.push({ public_id: publicId, url: newImageUrl });
};

// Function to delete an image from Cloudinary
const deleteImage = async (publicId, index) => {
    try {
        await axios.delete(`/image/delete/${publicId}`); // Laravel API call

        // Remove image from list after successful deletion
        imageList.value.splice(index, 1);
        isModalOpen.value = false
    } catch (error) {
        console.error("Error deleting image:", error);
        isModalOpen.value = false
    }
};
</script>
