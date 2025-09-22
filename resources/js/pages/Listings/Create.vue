<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea/index';
import AppLayout from '@/layouts/AppLayout.vue';
import listingRoutes from '@/routes/listings';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle, Upload, X } from 'lucide-vue-next';
import { ref } from 'vue';

interface Category {
  id: number;
  name: string;
  slug: string;
}

interface Props {
  remainingListings: number;
  categories: Category[];
}

defineProps<Props>();

// Main image
const mainImagePreview = ref<string | null>(null);
const mainImageFile = ref<File | null>(null);

// Additional images
const additionalImagePreviews = ref<string[]>([]);
const additionalImageFiles = ref<File[]>([]);

const handleMainImageUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (file) {
    mainImageFile.value = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      mainImagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};

const removeMainImage = () => {
  mainImagePreview.value = null;
  mainImageFile.value = null;
  const input = document.getElementById('main_image') as HTMLInputElement;
  if (input) input.value = '';
};

const handleAdditionalImagesUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const files = target.files;

  if (files) {
    Array.from(files).forEach(file => {
      if (additionalImageFiles.value.length < 4) { // Limit to 4 additional images
        additionalImageFiles.value.push(file);
        const reader = new FileReader();
        reader.onload = (e) => {
          additionalImagePreviews.value.push(e.target?.result as string);
        };
        reader.readAsDataURL(file);
      }
    });
  }
};

const removeAdditionalImage = (index: number) => {
  additionalImagePreviews.value.splice(index, 1);
  additionalImageFiles.value.splice(index, 1);
};

// Categories are now passed from the backend as props

const conditions = [
  { value: 'new', label: 'New' },
  { value: 'like_new', label: 'Like New' },
  { value: 'used', label: 'Used' },
  { value: 'needs_repair', label: 'Needs Repair' },
];

const currencies = [
  { value: 'eur', label: '€ EUR' },
  { value: 'usd', label: '$ USD' },
  { value: 'bgn', label: 'лв BGN' },
];
</script>

<template>
  <AppLayout title="Create Listing">

    <Head title="Create Listing" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
      <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
          <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
              Create New Listing
            </h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">
              You have {{ remainingListings }} {{ remainingListings === 1 ? 'listing' : 'listings' }} remaining.
            </p>
          </div>

          <Form v-bind="listingRoutes.store.form()" enctype="multipart/form-data" v-slot="{ errors, processing }"
            class="space-y-6">
            <!-- Title -->
            <div>
              <Label for="title">Title *</Label>
              <Input id="title" name="title" type="text" required
                placeholder="e.g., Racing Go-Kart Tony Kart Racer 401R" class="mt-1" />
              <p v-if="errors.title" class="text-red-600 text-sm mt-1">{{ errors.title }}</p>
            </div>

            <!-- Description -->
            <div>
              <Label for="description">Description *</Label>
              <Textarea id="description" name="description" required :rows="4"
                placeholder="Describe your item in detail. Include condition, features, specifications, etc."
                class="mt-1" />
              <p v-if="errors.description" class="text-red-600 text-sm mt-1">{{ errors.description }}</p>
            </div>

            <!-- Category and Condition -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <Label for="category_id">Category *</Label>
                <select id="category_id" name="category_id" required
                  class="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                  <option value="">Select category</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
                <p v-if="errors.category_id" class="text-red-600 text-sm mt-1">{{ errors.category_id }}</p>
              </div>

              <div>
                <Label for="condition">Condition *</Label>
                <select id="condition" name="condition" required
                  class="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                  <option value="">Select condition</option>
                  <option v-for="condition in conditions" :key="condition.value" :value="condition.value">
                    {{ condition.label }}
                  </option>
                </select>
                <p v-if="errors.condition" class="text-red-600 text-sm mt-1">{{ errors.condition }}</p>
              </div>
            </div>

            <!-- Price and Currency -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <Label for="price">Price *</Label>
                <Input id="price" name="price" type="number" step="0.01" min="0" required placeholder="0.00"
                  class="mt-1" />
                <p v-if="errors.price" class="text-red-600 text-sm mt-1">{{ errors.price }}</p>
              </div>

              <div>
                <Label for="currency">Currency *</Label>
                <select id="currency" name="currency" required
                  class="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                  <option value="">Select currency</option>
                  <option v-for="currency in currencies" :key="currency.value" :value="currency.value">
                    {{ currency.label }}
                  </option>
                </select>
                <p v-if="errors.currency" class="text-red-600 text-sm mt-1">{{ errors.currency }}</p>
              </div>
            </div>

            <!-- Location -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <Label for="country">Country *</Label>
                <Input id="country" name="country" type="text" required placeholder="e.g., Bulgaria" class="mt-1" />
                <p v-if="errors.country" class="text-red-600 text-sm mt-1">{{ errors.country }}</p>
              </div>

              <div>
                <Label for="state_province">State/Province</Label>
                <Input id="state_province" name="state_province" type="text" placeholder="e.g., Sofia" class="mt-1" />
                <p v-if="errors.state_province" class="text-red-600 text-sm mt-1">{{ errors.state_province }}</p>
              </div>

              <div>
                <Label for="city">City *</Label>
                <Input id="city" name="city" type="text" required placeholder="e.g., Sofia" class="mt-1" />
                <p v-if="errors.city" class="text-red-600 text-sm mt-1">{{ errors.city }}</p>
              </div>
            </div>

            <!-- Main Image Upload -->
            <div>
              <Label for="main_image">Main Image *</Label>

              <!-- Main Image Preview -->
              <div v-if="mainImagePreview" class="mt-2 relative">
                <img :src="mainImagePreview" alt="Main image preview" class="w-full h-48 object-cover rounded-lg" />
                <button @click="removeMainImage" type="button"
                  class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                  <X class="w-4 h-4" />
                </button>
              </div>

              <!-- Main Image Upload Area -->
              <div v-else
                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                <div class="space-y-1 text-center">
                  <Upload class="mx-auto h-12 w-12 text-gray-400" />
                  <div class="flex text-sm text-gray-600 dark:text-gray-300">
                    <label for="main_image"
                      class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                      <span>Upload main image</span>
                      <input id="main_image" name="main_image" type="file" accept="image/*" class="sr-only"
                        @change="handleMainImageUpload" />
                    </label>
                    <p class="pl-1">or drag and drop</p>
                  </div>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    PNG, JPG, GIF, WebP up to 10MB
                  </p>
                </div>
              </div>

              <p v-if="errors.main_image" class="text-red-600 text-sm mt-1">{{ errors.main_image }}</p>
            </div>

            <!-- Additional Images Upload -->
            <div>
              <Label for="additional_images">Additional Images (optional, up to 4)</Label>

              <!-- Additional Images Preview -->
              <div v-if="additionalImagePreviews.length > 0" class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div v-for="(preview, index) in additionalImagePreviews" :key="index" class="relative">
                  <img :src="preview" :alt="`Additional image ${index + 1}`" class="w-full h-24 object-cover rounded-lg" />
                  <button @click="removeAdditionalImage(index)" type="button"
                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                    <X class="w-3 h-3" />
                  </button>
                </div>
              </div>

              <!-- Additional Images Upload Area -->
              <div v-if="additionalImageFiles.length < 4"
                class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                <div class="space-y-1 text-center">
                  <Upload class="mx-auto h-8 w-8 text-gray-400" />
                  <div class="flex text-sm text-gray-600 dark:text-gray-300">
                    <label for="additional_images"
                      class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                      <span>Upload {{ additionalImageFiles.length === 0 ? 'additional images' : 'more images' }}</span>
                      <input id="additional_images" name="additional_images[]" type="file" accept="image/*" multiple class="sr-only"
                        @change="handleAdditionalImagesUpload" />
                    </label>
                    <p class="pl-1">or drag and drop</p>
                  </div>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    PNG, JPG, GIF, WebP up to 10MB each ({{ 4 - additionalImageFiles.length }} slots remaining)
                  </p>
                </div>
              </div>

              <p v-if="errors.additional_images" class="text-red-600 text-sm mt-1">{{ errors.additional_images }}</p>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
              <Button type="submit" :disabled="processing" class="w-full">
                <LoaderCircle v-if="processing" class="w-4 h-4 mr-2 animate-spin" />
                Create Listing
              </Button>
            </div>
          </Form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>