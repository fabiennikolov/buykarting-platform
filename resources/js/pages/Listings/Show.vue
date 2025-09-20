<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { 
  MapPin, 
  Calendar,
  User,
  Edit,
  Trash2,
  ArrowLeft,
  MessageCircle
} from 'lucide-vue-next';

interface User {
  id: number;
  name: string;
  email: string;
  type: string;
  country: string;
  state_province?: string;
  city: string;
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
  created_at: string;
  updated_at: string;
  user: User;
}

interface Props {
  listing: Listing;
  relatedListings?: Listing[];
  canEdit?: boolean;
}

const props = defineProps<Props>();

const formatPrice = (price: number, currency: string) => {
  const symbols = {
    EUR: '€',
    USD: '$',
    BGN: 'лв'
  };
  const symbol = symbols[currency as keyof typeof symbols] || currency;
  return `${symbol}${price.toLocaleString()}`;
};

const formatCategory = (category: string) => {
  return category.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
};

const formatCondition = (condition: string) => {
  return condition.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
};

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const deleteListing = () => {
  if (confirm('Are you sure you want to delete this listing? This action cannot be undone.')) {
    router.delete(`/listings/${props.listing.id}`, {
      onSuccess: () => {
        router.visit('/listings');
      }
    });
  }
};
</script>

<template>
  <AppLayout :title="listing.title">
    <Head :title="listing.title" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Button -->
        <div class="mb-6">
          <Link href="/listings" class="inline-flex items-center text-blue-600 hover:text-blue-700 dark:text-blue-400">
            <ArrowLeft class="w-4 h-4 mr-2" />
            Back to Listings
          </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
          
          <!-- Image Section -->
          <div v-if="listing.image_path" class="aspect-video bg-gray-100 dark:bg-gray-700">
            <img 
              :src="`/storage/${listing.image_path}`" 
              :alt="listing.title"
              class="w-full h-full object-cover"
            />
          </div>
          <div v-else class="aspect-video bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
            <div class="text-center text-gray-500 dark:text-gray-400">
              <svg class="mx-auto h-16 w-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <p>No image available</p>
            </div>
          </div>

          <div class="p-8">
            
            <!-- Header Section -->
            <div class="flex justify-between items-start mb-6">
              <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                  {{ listing.title }}
                </h1>
                <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-300">
                  <Badge variant="secondary">{{ formatCategory(listing.category) }}</Badge>
                  <Badge :variant="listing.condition === 'new' ? 'default' : 'outline'">
                    {{ formatCondition(listing.condition) }}
                  </Badge>
                  <span class="flex items-center">
                    <Calendar class="w-4 h-4 mr-1" />
                    {{ formatDate(listing.created_at) }}
                  </span>
                </div>
              </div>
              
              <!-- Price -->
              <div class="text-right">
                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                  {{ formatPrice(listing.price, listing.currency) }}
                </div>
              </div>
            </div>

            <!-- Description -->
            <div class="mb-8">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Description</h2>
              <div class="prose dark:prose-invert max-w-none">
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ listing.description }}</p>
              </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
              
              <!-- Location -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                  <MapPin class="w-5 h-5 mr-2" />
                  Location
                </h3>
                <div class="space-y-1 text-gray-600 dark:text-gray-300">
                  <p>{{ listing.city }}<span v-if="listing.state_province">, {{ listing.state_province }}</span></p>
                  <p>{{ listing.country }}</p>
                </div>
              </div>

              <!-- Seller Info -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                  <User class="w-5 h-5 mr-2" />
                  Seller
                </h3>
                <div class="space-y-1 text-gray-600 dark:text-gray-300">
                  <p class="font-medium">{{ listing.user.name }}</p>
                  <Badge variant="outline" class="text-xs">{{ formatCondition(listing.user.type) }}</Badge>
                  <p class="text-sm">{{ listing.user.city }}, {{ listing.user.country }}</p>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
              <template v-if="canEdit">
                <Link :href="`/listings/${listing.id}/edit`">
                  <Button variant="outline" class="flex items-center">
                    <Edit class="w-4 h-4 mr-2" />
                    Edit Listing
                  </Button>
                </Link>
                <Button variant="destructive" class="flex items-center" @click="deleteListing">
                  <Trash2 class="w-4 h-4 mr-2" />
                  Delete
                </Button>
              </template>
              <template v-else>
                <Button class="flex items-center">
                  <MessageCircle class="w-4 h-4 mr-2" />
                  Contact Seller
                </Button>
              </template>
            </div>
          </div>
        </div>

        <!-- Related Listings -->
        <div v-if="relatedListings && relatedListings.length > 0" class="mt-12">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Related Listings</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <Link 
              v-for="related in relatedListings" 
              :key="related.id" 
              :href="`/listings/${related.id}`"
              class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow"
            >
              <div class="aspect-video bg-gray-100 dark:bg-gray-700">
                <img 
                  v-if="related.image_path"
                  :src="`/storage/${related.image_path}`" 
                  :alt="related.title"
                  class="w-full h-full object-cover"
                />
                <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                  No image
                </div>
              </div>
              <div class="p-4">
                <h3 class="font-medium text-gray-900 dark:text-white mb-2 line-clamp-2">{{ related.title }}</h3>
                <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                  {{ formatPrice(related.price, related.currency) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ related.city }}, {{ related.country }}</p>
              </div>
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>