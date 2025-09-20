<template>
  <AppLayout>
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
          Karting Equipment Marketplace
        </h1>
        <p class="text-gray-600 dark:text-gray-300">
          Find the best go-karts, parts, and accessories for your karting needs
        </p>
      </div>

      <!-- Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
          Filter Listings
        </h2>
        <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Search
            </label>
            <input
              v-model="form.search"
              type="text"
              placeholder="Search listings..."
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Category
            </label>
            <select
              v-model="form.category"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
              <option value="">All Categories</option>
              <option value="go_kart">Go Karts</option>
              <option value="parts">Parts</option>
              <option value="accessories">Accessories</option>
              <option value="consumables">Consumables</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Condition
            </label>
            <select
              v-model="form.condition"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
              <option value="">All Conditions</option>
              <option value="new">New</option>
              <option value="used">Used</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Min Price
            </label>
            <input
              v-model="form.min_price"
              type="number"
              min="0"
              step="0.01"
              placeholder="0"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Max Price
            </label>
            <input
              v-model="form.max_price"
              type="number"
              min="0"
              step="0.01"
              placeholder="10000"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
          </div>

          <div class="flex items-end">
            <button
              type="submit"
              class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors"
            >
              Filter
            </button>
          </div>
        </form>
      </div>

      <!-- Create Listing Button -->
      <div class="mb-6 flex justify-between items-center">
        <div class="text-gray-600 dark:text-gray-400">
          {{ listings.total }} listings found
        </div>
        <Link
          v-if="$page.props.auth.user"
          :href="listingRoutes.create.url()"
          class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors"
        >
          Create Listing
        </Link>
      </div>

      <!-- Listings Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        <div
          v-for="listing in listings.data"
          :key="listing.id"
          class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow"
        >
          <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-700">
            <img
              :src="listing.image_path ? `/storage/${listing.image_path}` : '/images/placeholder.jpg'"
              :alt="listing.title"
              class="w-full h-48 object-cover"
            />
          </div>

          <div class="p-4">
            <div class="mb-2">
              <span
                class="inline-block px-2 py-1 text-xs font-semibold rounded-full"
                :class="getCategoryClass(listing.category)"
              >
                {{ getCategoryLabel(listing.category) }}
              </span>
              <span
                class="inline-block ml-2 px-2 py-1 text-xs font-semibold rounded-full"
                :class="getConditionClass(listing.condition)"
              >
                {{ listing.condition }}
              </span>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
              {{ listing.title }}
            </h3>

            <p class="text-gray-600 dark:text-gray-300 text-sm mb-3 line-clamp-2">
              {{ listing.description }}
            </p>

            <div class="flex items-center justify-between">
              <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                {{ listing.price }} {{ listing.currency }}
              </div>
              <Link
                :href="listingRoutes.show.url(listing.id)"
                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors"
              >
                View
              </Link>
            </div>

            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
              {{ listing.city }}, {{ listing.country }}
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="listings.data.length === 0" class="text-center py-12">
        <div class="text-gray-400 dark:text-gray-600 text-6xl mb-4">🏁</div>
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
          No listings found
        </h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4">
          Try adjusting your filters or create a new listing.
        </p>
        <Link
          v-if="$page.props.auth.user"
          :href="listingRoutes.create.url()"
          class="inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors"
        >
          Create First Listing
        </Link>
      </div>

      <!-- Pagination -->
      <div v-if="listings.links && listings.data.length > 0" class="flex justify-center">
        <nav class="flex space-x-1">
          <template v-for="link in listings.links" :key="link.label">
            <Link
              v-if="link.url"
              :href="link.url"
              :class="[
                'px-3 py-2 text-sm border rounded transition-colors',
                link.active
                  ? 'bg-blue-600 text-white border-blue-600'
                  : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700'
              ]"
              v-html="link.label"
            />
            <span
              v-else
              :class="[
                'px-3 py-2 text-sm border rounded cursor-not-allowed',
                'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 border-gray-300 dark:border-gray-600'
              ]"
              v-html="link.label"
            />
          </template>
        </nav>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import listingRoutes from '@/routes/listings'

interface User {
  id: number
  name: string
}

interface Listing {
  id: number
  title: string
  description: string
  category: string
  condition: string
  price: number
  currency: string
  country: string
  city: string
  image_path?: string
  user: User
}

interface Props {
  listings: {
    data: Listing[]
    total: number
    links: any[]
  }
  filters: {
    search?: string
    category?: string
    condition?: string
    country?: string
    city?: string
    min_price?: number
    max_price?: number
  }
}

const props = defineProps<Props>()

const form = ref({
  search: props.filters.search || '',
  category: props.filters.category || '',
  condition: props.filters.condition || '',
  min_price: props.filters.min_price || '',
  max_price: props.filters.max_price || '',
})

const applyFilters = () => {
  const params = Object.fromEntries(
    Object.entries(form.value).filter(([_, value]) => value !== '' && value !== null)
  )
  
  router.get(listingRoutes.index.url(), params, {
    preserveState: true,
    preserveScroll: true,
  })
}

const getCategoryClass = (category: string) => {
  const classes = {
    go_kart: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    parts: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    accessories: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
    consumables: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
  }
  return classes[category as keyof typeof classes] || 'bg-gray-100 text-gray-800'
}

const getCategoryLabel = (category: string) => {
  const labels = {
    go_kart: 'Go Kart',
    parts: 'Parts',
    accessories: 'Accessories',
    consumables: 'Consumables',
  }
  return labels[category as keyof typeof labels] || category
}

const getConditionClass = (condition: string) => {
  return condition === 'new'
    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
}
</script>