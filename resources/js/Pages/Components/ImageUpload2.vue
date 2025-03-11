<template>
    <div class="flex flex-col items-center justify-center w-full">
        <!-- Drag & Drop or Click to Upload -->
        <div
            class="w-full max-w-lg p-6 border-2 border-dashed rounded-lg cursor-pointer bg-gray-100 hover:bg-gray-200"
            :class="{ 'border-blue-500': isDragging }"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop"
            @click="triggerFileInput"
        >
            <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" accept="image/*" capture="environment" />
            <div class="text-center">
                <svg class="w-12 h-12 mx-auto text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 16V12m0 0L4 15m3-3l3 3m8 1v-4m0 0l3 3m-3-3l-3 3M12 3v12m0 0L9 12m3 3l3-3" />
                </svg>
                <p class="mt-2 text-gray-600">Click or drag an image to upload</p>
                <p class="text-sm text-gray-400">Only JPG, PNG, and GIF files under 5MB</p>
            </div>
        </div>

        <!-- Image Preview & Upload Progress -->
        <div v-if="previewUrl" class="mt-4">
            <img :src="previewUrl" alt="Preview" class="w-32 h-32 rounded-lg shadow-lg" />
            <p v-if="loading" class="text-blue-600 mt-2">Loading image...</p>
        </div>

        <!-- Upload Button -->
        <button
            @click="uploadImage"
            class="px-4 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:bg-gray-400"
            :disabled="!imageReady"
        >
            Upload
        </button>

        <p v-if="uploadMessage" class="mt-2 text-green-600">{{ uploadMessage }}</p>
        <p v-if="uploadError" class="mt-2 text-red-600">{{ uploadError }}</p>
    </div>
</template>

<script setup>
import { ref, onMounted, defineProps, defineEmits } from "vue";
import axios from "axios";

// Define props from parent component
const props = defineProps({
    addressId: String,  // Address ID (String or Number, depending on usage)
});

// Define event emitter
const emit = defineEmits(["imageUploaded"]);

const fileInput = ref(null);
const selectedFile = ref(null);
const previewUrl = ref(null);
const uploadMessage = ref("");
const uploadError = ref("");
const isDragging = ref(false);
const csrfToken = ref(null);
const loading = ref(false);
const imageReady = ref(false); // Track if image is fully loaded

onMounted(() => {
    const tokenElement = document.querySelector('meta[name="csrf-token"]');
    if (tokenElement) {
        csrfToken.value = tokenElement.getAttribute("content");
    }
});

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        loading.value = true;
        imageReady.value = false;
        readImage(file);
    }
};

const handleDrop = (event) => {
    isDragging.value = false;
    const file = event.dataTransfer.files[0];
    if (file) {
        loading.value = true;
        imageReady.value = false;
        readImage(file);
    }
};

// Read image into memory before allowing upload
const readImage = (file) => {
    const reader = new FileReader();

    reader.onload = () => {
        previewUrl.value = reader.result; // Show preview
        selectedFile.value = file;
        loading.value = false;
        imageReady.value = true; // Image is now ready for upload
    };

    reader.onerror = () => {
        uploadError.value = "Error loading image.";
        loading.value = false;
    };

    reader.readAsDataURL(file);
};

const uploadImage = async () => {
    if (!selectedFile.value) {
        uploadError.value = "No file selected.";
        return;
    }

    const formData = new FormData();
    formData.append("image", selectedFile.value);
    formData.append("address_id", props.addressId);

    try {
        const response = await axios.post("/upload", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                "X-CSRF-TOKEN": csrfToken.value,
            },
            onUploadProgress: (progressEvent) => {
                const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                console.log(`Upload Progress: ${percentCompleted}%`);
            }
        });

        if (response.data.public_id) {
            uploadMessage.value = "Image uploaded successfully!";
            setTimeout(() => {
                uploadMessage.value = "";
            }, 1000);
        }

        // Emit the event to the parent component with the public ID
        emit("imageUploaded", publicId);

        previewUrl.value = response.data.url; // Use Cloudinary URL if returned
    } catch (error) {
        uploadError.value = "Upload failed. Try again.";

        setTimeout(() => {
            uploadError.value = "";
        }, 2000);
    }
};
</script>

<style scoped>
input[type="file"] {
    display: none;
}
</style>
