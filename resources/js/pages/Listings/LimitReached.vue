<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Crown, Plus } from 'lucide-vue-next';

interface Subscription {
  id: number;
  plan: string;
  listings_limit: number;
  status: string;
}

interface Props {
  subscription: Subscription;
  remainingListings: number;
}

defineProps<Props>();
</script>

<template>
  <AppLayout title="Listing Limit Reached">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
      <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8 text-center">
          <div class="w-16 h-16 mx-auto mb-6 bg-yellow-100 dark:bg-yellow-900/20 rounded-full flex items-center justify-center">
            <Crown class="w-8 h-8 text-yellow-600 dark:text-yellow-500" />
          </div>
          
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
            Listing Limit Reached
          </h1>
          
          <p class="text-gray-600 dark:text-gray-300 mb-6">
            You've reached your {{ subscription.plan }} plan limit of {{ subscription.listings_limit }} 
            {{ subscription.listings_limit === 1 ? 'listing' : 'listings' }}.
            Upgrade your subscription to create more listings and grow your karting business.
          </p>

          <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
            <div class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-gray-500 dark:text-gray-400">Current Plan</span>
                <p class="font-medium text-gray-900 dark:text-white capitalize">
                  {{ subscription.plan }}
                </p>
              </div>
              <div>
                <span class="text-gray-500 dark:text-gray-400">Listings Used</span>
                <p class="font-medium text-gray-900 dark:text-white">
                  {{ subscription.listings_limit - remainingListings }} / {{ subscription.listings_limit }}
                </p>
              </div>
            </div>
          </div>

          <div class="space-y-4">
            <Link href="/subscriptions">
              <Button class="w-full">
                <Crown class="w-4 h-4 mr-2" />
                Upgrade Subscription
              </Button>
            </Link>
            
            <Link href="/my-listings">
              <Button variant="outline" class="w-full">
                View My Listings
              </Button>
            </Link>
          </div>

          <p class="text-xs text-gray-500 dark:text-gray-400 mt-6">
            Need help? Contact our support team for assistance with upgrading your subscription.
          </p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>