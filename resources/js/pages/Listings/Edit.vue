<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea/index';
import AppLayout from '@/layouts/AppLayout.vue';
import listingRoutes from '@/routes/listings';
import { Form, Head, Link } from '@inertiajs/vue3';
import { LoaderCircle, Upload, X, ArrowLeft } from 'lucide-vue-next';
import { ref } from 'vue';

interface User {
  id: number;
  name: string;
  email: string;
}

interface Listing {
  id: number;
  title: string;
  description: string;
  category: string;
  condition: string;
  price: number;
  currency: string;
  country: string;
  state_province?: string;
  city: string;
  image_path?: string;
  status: string;
  user: User;
}

interface Props {
  listing: Listing;
}

const props = defineProps<Props>();

const imagePreview = ref<string | null>(
  props.listing.image_path ? `/storage/${props.listing.image_path}` : null
);
const imageFile = ref<File | null>(null);

const handleImageUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (file) {
    imageFile.value = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};

const removeImage = () => {
  imagePreview.value = null;
  imageFile.value = null;
  const input = document.getElementById('image') as HTMLInputElement;
  if (input) input.value = '';
  const changeInput = document.getElementById('image-change') as HTMLInputElement;
  if (changeInput) changeInput.value = '';
};

const categories = [
  { value: 'go_kart', label: 'Go-Karts' },
  { value: 'engine', label: 'Engines' },
  { value: 'chassis', label: 'Chassis' },
  { value: 'wheels_tires', label: 'Wheels & Tires' },
  { value: 'safety_gear', label: 'Safety Gear' },
  { value: 'parts', label: 'Parts' },
  { value: 'accessories', label: 'Accessories' },
  { value: 'consumables', label: 'Consumables' },
  { value: 'tools', label: 'Tools' },
];

const conditions = [
  { value: 'new', label: 'New' },
  { value: 'like_new', label: 'Like New' },
  { value: 'used', label: 'Used' },
  { value: 'needs_repair', label: 'Needs Repair' },
];

const currencies = [
  { value: 'EUR', label: '€ EUR' },
  { value: 'USD', label: '$ USD' },
  { value: 'BGN', label: 'лв BGN' },
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

          <Form v-bind="listingRoutes.update.form(listing.id)" enctype="multipart/form-data"
            v-slot="{ errors, processing }" class="space-y-6">
            <!-- Title -->
            <div>
              <Label for="title">Title *</Label>
              <Input id="title" name="title" type="text" required :value="listing.title"
                placeholder="e.g., Racing Go-Kart Tony Kart Racer 401R" class="mt-1" />
              <p v-if="errors.title" class="text-red-600 text-sm mt-1">{{ errors.title }}</p>
            </div>

            <!-- Description -->
            <div>
              <Label for="description">Description *</Label>
              <Textarea id="description" name="description" required :rows="4" :value="listing.description"
                placeholder="Describe your item in detail. Include condition, features, specifications, etc."
                class="mt-1" />
              <p v-if="errors.description" class="text-red-600 text-sm mt-1">{{ errors.description }}</p>
            </div>

            <!-- Category and Condition -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <Label for="category">Category *</Label>
                <select id="category" name="category" required :value="listing.category"
                  class="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                  <option value="">Select category</option>
                  <option v-for="category in categories" :key="category.value" :value="category.value">
                    {{ category.label }}
                  </option>
                </select>
                <p v-if="errors.category" class="text-red-600 text-sm mt-1">{{ errors.category }}</p>
              </div>

              <div>
                <Label for="condition">Condition *</Label>
                <select id="condition" name="condition" required :value="listing.condition"
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
                <Input id="price" name="price" type="number" step="0.01" min="0" required :value="listing.price"
                  placeholder="0.00" class="mt-1" />
                <p v-if="errors.price" class="text-red-600 text-sm mt-1">{{ errors.price }}</p>
              </div>

              <div>
                <Label for="currency">Currency *</Label>
                <select id="currency" name="currency" required :value="listing.currency"
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
                <Input id="country" name="country" type="text" required :value="listing.country"
                  placeholder="e.g., Bulgaria" class="mt-1" />
                <p v-if="errors.country" class="text-red-600 text-sm mt-1">{{ errors.country }}</p>
              </div>

              <div>
                <Label for="state_province">State/Province</Label>
                <Input id="state_province" name="state_province" type="text" :value="listing.state_province"
                  placeholder="e.g., Sofia" class="mt-1" />
                <p v-if="errors.state_province" class="text-red-600 text-sm mt-1">{{ errors.state_province }}</p>
              </div>

              <div>
                <Label for="city">City *</Label>
                <Input id="city" name="city" type="text" required :value="listing.city" placeholder="e.g., Sofia"
                  class="mt-1" />
                <p v-if="errors.city" class="text-red-600 text-sm mt-1">{{ errors.city }}</p>
              </div>
            </div>

            <!-- Status -->
            <div>
              <Label for="status">Status *</Label>
              <select id="status" name="status" required :value="listing.status"
                class="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                <option v-for="status in statuses" :key="status.value" :value="status.value">
                  {{ status.label }}
                </option>
              </select>
              <p v-if="errors.status" class="text-red-600 text-sm mt-1">{{ errors.status }}</p>
            </div>

            <!-- Image Upload -->
            <div>
              <Label for="image">Image</Label>

              <!-- Image Preview -->
              <div v-if="imagePreview" class="mt-2 relative">
                <img :src="imagePreview" alt="Preview" class="max-w-full h-48 object-cover rounded-lg" />
                <button @click="removeImage" type="button"
                  class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                  <X class="w-4 h-4" />
                </button>
              </div>

              <!-- Upload Area -->
              <div v-else
                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                <div class="space-y-1 text-center">
                  <Upload class="mx-auto h-12 w-12 text-gray-400" />
                  <div class="flex text-sm text-gray-600 dark:text-gray-300">
                    <label for="image"
                      class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                      <span>Upload a file</span>
                      <input id="image" name="image" type="file" accept="image/*" class="sr-only"
                        @change="handleImageUpload" />
                    </label>
                    <p class="pl-1">or drag and drop</p>
                  </div>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    PNG, JPG, GIF up to 10MB
                  </p>
                </div>
              </div>

              <!-- Change image when preview is shown -->
              <div v-if="imagePreview" class="mt-2">
                <label for="image-change"
                  class="cursor-pointer inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  <Upload class="w-4 h-4 mr-2" />
                  Change Image
                  <input id="image-change" name="image" type="file" accept="image/*" class="sr-only"
                    @change="handleImageUpload" />
                </label>
              </div>

              <p v-if="errors.image" class="text-red-600 text-sm mt-1">{{ errors.image }}</p>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
              <Button type="submit" :disabled="processing" class="w-full">
                <LoaderCircle v-if="processing" class="w-4 h-4 mr-2 animate-spin" />
                Update Listing
              </Button>
            </div>
          </Form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>