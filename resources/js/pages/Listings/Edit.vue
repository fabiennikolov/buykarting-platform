<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { LoaderCircle, Upload, X, ArrowLeft } from 'lucide-vue-next';
import { ref, computed } from 'vue';

interface User {
  id: number;
  name: string;
  email: string;
}

interface Category {
  id: number;
  name: string;
  slug: string;
}

interface Media {
  id: number;
  url: string;
  thumb_url: string;
  preview_url: string;
}

interface Listing {
  id: number;
  title: string;
  description: string;
  category_id: number;
  category?: Category;
  condition: string;
  price: number;
  currency: string;
  country: string;
  state_province?: string;
  city: string;
  status: string;
  user: User;
  media: Media[];
}

interface Props {
  listing: Listing;
  categories: Category[];
}

const props = defineProps<Props>();

const form = useForm({
  title: props.listing.title,
  description: props.listing.description,
  category_id: props.listing.category_id,
  condition: props.listing.condition,
  price: props.listing.price,
  currency: props.listing.currency,
  country: props.listing.country,
  state_province: props.listing.state_province,
  city: props.listing.city,
  status: props.listing.status,
  main_image: null as File | null,
  additional_images: [] as File[],
  remove_media: [] as number[],
});


// Use computed properties for better reactivity to props changes
const existingMedia = computed(() => {
  console.log('🎬 existingMedia computed, props.listing.media:', props.listing.media);
  return props.listing.media || [];
});

const mainImage = computed(() => {
  const media = existingMedia.value[0] || null;
  console.log('🖼️ mainImage computed:', media);
  return media;
});

const additionalImages = computed(() => {
  const images = existingMedia.value.slice(1) || [];
  console.log('📷 additionalImages computed:', images);
  return images;
});

// New uploads
const newMainImagePreview = ref<string | null>(null);
const newMainImageFile = ref<File | null>(null);
const newAdditionalImagePreviews = ref<string[]>([]);
const newAdditionalImageFiles = ref<File[]>([]);

const handleMainImageUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (file) {
    newMainImageFile.value = file;
    form.main_image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      newMainImagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};

const handleAdditionalImagesUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const files = target.files;

  if (files) {
    Array.from(files).forEach(file => {
      const totalAdditional = additionalImages.value.length + newAdditionalImageFiles.value.length;
      if (totalAdditional < 4) { // Limit to 4 additional images
        newAdditionalImageFiles.value.push(file);
        form.additional_images.push(file);
        const reader = new FileReader();
        reader.onload = (e) => {
          newAdditionalImagePreviews.value.push(e.target?.result as string);
        };
        reader.readAsDataURL(file);
      }
    });
  }
};

const submit = () => {
  // Use POST with _method for file uploads since PUT doesn't work well with FormData
  form.transform((data) => ({
    ...data,
    _method: 'PUT'
  })).post(`/listings/${props.listing.id}`, {
    onSuccess: () => {
      // Reset the new upload state since we'll be redirected back with fresh data
      newMainImagePreview.value = null;
      newMainImageFile.value = null;
      newAdditionalImagePreviews.value = [];
      newAdditionalImageFiles.value = [];
      form.main_image = null;
      form.additional_images = [];
      form.remove_media = [];

      // Clear file inputs
      const mainImageInput = document.getElementById('main_image') as HTMLInputElement;
      if (mainImageInput) mainImageInput.value = '';
      const additionalImagesInput = document.getElementById('additional_images') as HTMLInputElement;
      if (additionalImagesInput) additionalImagesInput.value = '';
    },
  });
};

const removeMainImage = () => {
  if (mainImage.value) {
    console.log('🗑️ Removing main image:', mainImage.value.id);
    form.remove_media.push(mainImage.value.id);
  }
  // Also clear new main image if set
  newMainImagePreview.value = null;
  newMainImageFile.value = null;
  form.main_image = null;
  const input = document.getElementById('main_image') as HTMLInputElement;
  if (input) input.value = '';
};

const removeAdditionalImage = (mediaId: number) => {
  console.log('🗑️ Removing additional image:', mediaId);
  form.remove_media.push(mediaId);
};

const removeNewAdditionalImage = (index: number) => {
  newAdditionalImagePreviews.value.splice(index, 1);
  newAdditionalImageFiles.value.splice(index, 1);
  form.additional_images.splice(index, 1);
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

const statuses = [
  { value: 'active', label: 'Active' },
  { value: 'draft', label: 'Draft' },
  { value: 'sold', label: 'Sold' },
];
</script>

<template>
  <AppLayout title="Edit Listing">

    <Head title="Edit Listing" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
      <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Back Button -->
        <div class="mb-6">
          <Link :href="`/listings/${listing.id}`"
            class="inline-flex items-center text-blue-600 hover:text-blue-700 dark:text-blue-400">
          <ArrowLeft class="w-4 h-4 mr-2" />
          Back to Listing
          </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
          <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
              Edit Listing
            </h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">
              Update your listing information
            </p>
          </div>

          <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">
            <!-- Title -->
            <div>
              <Label for="title">Title *</Label>
              <Input id="title" name="title" type="text" required v-model="form.title"
                placeholder="e.g., Racing Go-Kart Tony Kart Racer 401R" class="mt-1" />
              <p v-if="form.errors.title" class="text-red-600 text-sm mt-1">{{ form.errors.title }}</p>
            </div>

            <!-- Description -->
            <div>
              <Label for="description">Description *</Label>
              <textarea id="description" name="description" required rows="4" v-model="form.description"
                placeholder="Describe your item in detail. Include condition, features, specifications, etc."
                class="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"></textarea>
              <p v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{ form.errors.description }}</p>
            </div>

            <!-- Category and Condition -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <Label for="category">Category *</Label>
                <select id="category_id" name="category_id" required v-model="form.category_id"
                  class="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                  <option value="">Select category</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
                <p v-if="form.errors.category_id" class="text-red-600 text-sm mt-1">{{ form.errors.category_id }}</p>
              </div>

              <div>
                <Label for="condition">Condition *</Label>
                <select id="condition" name="condition" required v-model="form.condition"
                  class="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                  <option value="">Select condition</option>
                  <option v-for="condition in conditions" :key="condition.value" :value="condition.value">
                    {{ condition.label }}
                  </option>
                </select>
                <p v-if="form.errors.condition" class="text-red-600 text-sm mt-1">{{ form.errors.condition }}</p>
              </div>
            </div>

            <!-- Price and Currency -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <Label for="price">Price *</Label>
                <Input id="price" name="price" type="number" step="0.01" min="0" required v-model="form.price"
                  placeholder="0.00" class="mt-1" />
                <p v-if="form.errors.price" class="text-red-600 text-sm mt-1">{{ form.errors.price }}</p>
              </div>

              <div>
                <Label for="currency">Currency *</Label>
                <select id="currency" name="currency" required v-model="form.currency"
                  class="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                  <option value="">Select currency</option>
                  <option v-for="currency in currencies" :key="currency.value" :value="currency.value">
                    {{ currency.label }}
                  </option>
                </select>
                <p v-if="form.errors.currency" class="text-red-600 text-sm mt-1">{{ form.errors.currency }}</p>
              </div>
            </div>

            <!-- Location -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <Label for="country">Country *</Label>
                <Input id="country" name="country" type="text" required v-model="form.country"
                  placeholder="e.g., Bulgaria" class="mt-1" />
                <p v-if="form.errors.country" class="text-red-600 text-sm mt-1">{{ form.errors.country }}</p>
              </div>

              <div>
                <Label for="state_province">State/Province</Label>
                <Input id="state_province" name="state_province" type="text" v-model="form.state_province"
                  placeholder="e.g., Sofia" class="mt-1" />
                <p v-if="form.errors.state_province" class="text-red-600 text-sm mt-1">{{ form.errors.state_province }}
                </p>
              </div>

              <div>
                <Label for="city">City *</Label>
                <Input id="city" name="city" type="text" required v-model="form.city" placeholder="e.g., Sofia"
                  class="mt-1" />
                <p v-if="form.errors.city" class="text-red-600 text-sm mt-1">{{ form.errors.city }}</p>
              </div>
            </div>

            <!-- Status -->
            <div>
              <Label for="status">Status *</Label>
              <select id="status" name="status" required v-model="form.status"
                class="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                <option v-for="status in statuses" :key="status.value" :value="status.value">
                  {{ status.label }}
                </option>
              </select>
              <p v-if="form.errors.status" class="text-red-600 text-sm mt-1">{{ form.errors.status }}</p>
            </div>

            <!-- Main Image Upload -->
            <div>
              <Label for="main_image">Main Image</Label>

              <!-- Current Main Image -->
              <div v-if="mainImage" class="mt-2">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Current Main Image (ID: {{ mainImage.id }})
                </h4>
                <div class="relative inline-block">
                  <img :src="mainImage.thumb_url" :alt="'Current main image'"
                    class="w-full max-w-sm h-48 object-cover rounded-lg" />
                  <button @click="removeMainImage" type="button"
                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                    <X class="w-4 h-4" />
                  </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">URL: {{ mainImage.thumb_url }}</p>
              </div>

              <!-- New Main Image Preview -->
              <div v-if="newMainImagePreview" class="mt-2">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Main Image</h4>
                <div class="relative inline-block">
                  <img :src="newMainImagePreview" alt="New main image preview"
                    class="w-full max-w-sm h-48 object-cover rounded-lg" />
                  <button @click="removeMainImage" type="button"
                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                    <X class="w-4 h-4" />
                  </button>
                </div>
              </div>

              <!-- Main Image Upload Area -->
              <div v-if="!mainImage && !newMainImagePreview"
                class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
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

              <!-- Change Main Image Button -->
              <div v-if="mainImage && !newMainImagePreview" class="mt-2">
                <label for="main_image_change"
                  class="cursor-pointer inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  <Upload class="w-4 h-4 mr-2" />
                  Change Main Image
                  <input id="main_image_change" name="main_image" type="file" accept="image/*" class="sr-only"
                    @change="handleMainImageUpload" />
                </label>
              </div>

              <p v-if="form.errors.main_image" class="text-red-600 text-sm mt-1">{{ form.errors.main_image }}</p>
            </div>

            <!-- Additional Images Upload -->
            <div>
              <Label for="additional_images">Additional Images (up to 4)</Label>

              <!-- Current Additional Images -->
              <div v-if="additionalImages.length > 0" class="mt-2">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Current Additional Images ({{ additionalImages.length }})
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div v-for="media in additionalImages" :key="media.id" class="relative">
                    <img :src="media.thumb_url" :alt="`Additional image ${media.id}`"
                      class="w-full h-24 object-cover rounded-lg" />
                    <button @click="removeAdditionalImage(media.id)" type="button"
                      class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                      <X class="w-3 h-3" />
                    </button>
                  </div>
                </div>
              </div>

              <!-- New Additional Images Preview -->
              <div v-if="newAdditionalImagePreviews.length > 0" class="mt-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Additional Images</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div v-for="(preview, index) in newAdditionalImagePreviews" :key="`new-${index}`" class="relative">
                    <img :src="preview" :alt="`New additional image ${index + 1}`"
                      class="w-full h-24 object-cover rounded-lg" />
                    <button @click="removeNewAdditionalImage(index)" type="button"
                      class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                      <X class="w-3 h-3" />
                    </button>
                  </div>
                </div>
              </div>

              <!-- Additional Images Upload Area -->
              <div v-if="(additionalImages.length + newAdditionalImageFiles.length) < 4"
                class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                <div class="space-y-1 text-center">
                  <Upload class="mx-auto h-8 w-8 text-gray-400" />
                  <div class="flex text-sm text-gray-600 dark:text-gray-300">
                    <label for="additional_images"
                      class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                      <span>Upload additional images</span>
                      <input id="additional_images" name="additional_images[]" type="file" accept="image/*" multiple
                        class="sr-only" @change="handleAdditionalImagesUpload" />
                    </label>
                    <p class="pl-1">or drag and drop</p>
                  </div>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    PNG, JPG, GIF, WebP up to 10MB each ({{ 4 - (additionalImages.length +
                      newAdditionalImageFiles.length) }} slots remaining)
                  </p>
                </div>
              </div>

              <p v-if="form.errors.additional_images" class="text-red-600 text-sm mt-1">{{ form.errors.additional_images
                }}</p>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
              <Button type="submit" :disabled="form.processing" class="w-full">
                <LoaderCircle v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                Update Listing
              </Button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>