# 🚀 Inertia.js v2 Documentation for BuyKarting.com

## 📋 Overview

**Inertia.js** is a modern approach to building single-page applications using classic server-side routing and controllers. It works seamlessly with your Laravel 12 + Vue 3 + Inertia.js setup, providing a smooth development experience without the complexity of building separate APIs.

## 🔗 Navigation & Links

### Basic Navigation
```vue
<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <!-- Basic link -->
  <Link href="/listings">View Listings</Link>
  
  <!-- Link with preserve scroll -->
  <Link href="/listings" preserve-scroll>View Listings</Link>
  
  <!-- Link with method -->
  <Link href="/listings/1" method="delete" as="button">Delete</Link>
  
  <!-- Link with data -->
  <Link href="/listings" :data="{ search: 'kart' }">Search Karts</Link>
</template>
```

### Programmatic Navigation
```vue
<script setup>
import { router } from '@inertiajs/vue3'

// Basic navigation
router.visit('/listings')

// With data
router.post('/listings', {
  title: 'New Kart',
  price: 1500
})

// With options
router.get('/listings', { search: 'kart' }, {
  preserveState: true,
  preserveScroll: true
})
</script>
```

### Navigation Methods
```javascript
// GET request
router.get('/listings', { search: 'kart' })

// POST request
router.post('/listings', { title: 'New Kart' })

// PUT/PATCH request
router.put('/listings/1', { title: 'Updated Kart' })
router.patch('/listings/1', { price: 2000 })

// DELETE request
router.delete('/listings/1')

// Reload current page
router.reload()
```

## 📝 Forms

### Form Component (Recommended)
The `<Form>` component is the simplest way to handle forms in Inertia.js:

```vue
<script setup>
import { Form } from '@inertiajs/vue3'
</script>

<template>
  <Form
    action="/listings"
    method="post"
    #default="{
      errors,
      hasErrors,
      processing,
      progress,
      wasSuccessful,
      recentlySuccessful,
      setError,
      clearErrors,
      resetAndClearErrors,
      defaults,
      isDirty,
      reset,
      submit,
    }"
  >
    <input type="text" name="title" placeholder="Listing Title" />
    <div v-if="errors.title" class="text-red-500">{{ errors.title }}</div>
    
    <textarea name="description" placeholder="Description"></textarea>
    <div v-if="errors.description" class="text-red-500">{{ errors.description }}</div>
    
    <select name="category">
      <option value="go_kart">Go Kart</option>
      <option value="parts">Parts</option>
      <option value="accessories">Accessories</option>
      <option value="consumables">Consumables</option>
    </select>
    <div v-if="errors.category" class="text-red-500">{{ errors.category }}</div>
    
    <select name="condition">
      <option value="new">New</option>
      <option value="used">Used</option>
    </select>
    
    <input type="number" name="price" placeholder="Price" step="0.01" />
    <div v-if="errors.price" class="text-red-500">{{ errors.price }}</div>
    
    <select name="currency">
      <option value="EUR">EUR</option>
      <option value="BGN">BGN</option>
      <option value="USD">USD</option>
    </select>
    
    <input type="text" name="country" placeholder="Country" />
    <input type="text" name="city" placeholder="City" />
    
    <input type="file" name="image" accept="image/*" />
    <div v-if="errors.image" class="text-red-500">{{ errors.image }}</div>
    
    <button type="submit" :disabled="processing" class="btn-primary">
      {{ processing ? 'Creating...' : 'Create Listing' }}
    </button>
    
    <div v-if="wasSuccessful" class="text-green-500">
      Listing created successfully!
    </div>
  </Form>
</template>
```

### useForm Helper (Programmatic Control)
For more control over form behavior:

```vue
<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  description: '',
  category: 'go_kart',
  condition: 'used',
  price: '',
  currency: 'EUR',
  country: '',
  city: '',
  image: null
})

const submit = () => {
  form.post('/listings', {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      // Show success message or redirect
    },
    onError: (errors) => {
      console.log('Validation errors:', errors)
    }
  })
}

const handleImageChange = (event) => {
  form.image = event.target.files[0]
}
</script>

<template>
  <form @submit.prevent="submit">
    <div class="form-group">
      <label for="title">Title</label>
      <input 
        id="title"
        type="text" 
        v-model="form.title" 
        :class="{ 'border-red-500': form.errors.title }"
        class="form-input"
      />
      <div v-if="form.errors.title" class="text-red-500 text-sm">
        {{ form.errors.title }}
      </div>
    </div>
    
    <div class="form-group">
      <label for="description">Description</label>
      <textarea 
        id="description"
        v-model="form.description"
        :class="{ 'border-red-500': form.errors.description }"
        class="form-textarea"
      ></textarea>
      <div v-if="form.errors.description" class="text-red-500 text-sm">
        {{ form.errors.description }}
      </div>
    </div>
    
    <div class="form-group">
      <label for="category">Category</label>
      <select 
        id="category"
        v-model="form.category"
        class="form-select"
      >
        <option value="go_kart">Go Kart</option>
        <option value="parts">Parts</option>
        <option value="accessories">Accessories</option>
        <option value="consumables">Consumables</option>
      </select>
    </div>
    
    <div class="form-group">
      <label for="price">Price</label>
      <input 
        id="price"
        type="number" 
        v-model="form.price"
        step="0.01"
        :class="{ 'border-red-500': form.errors.price }"
        class="form-input"
      />
      <div v-if="form.errors.price" class="text-red-500 text-sm">
        {{ form.errors.price }}
      </div>
    </div>
    
    <div class="form-group">
      <label for="image">Image</label>
      <input 
        id="image"
        type="file" 
        @input="handleImageChange"
        accept="image/*"
        class="form-input"
      />
      <div v-if="form.errors.image" class="text-red-500 text-sm">
        {{ form.errors.image }}
      </div>
    </div>
    
    <!-- Progress bar for file uploads -->
    <div v-if="form.progress" class="mb-4">
      <div class="w-full bg-gray-200 rounded-full h-2">
        <div 
          class="bg-blue-600 h-2 rounded-full transition-all duration-300"
          :style="{ width: form.progress.percentage + '%' }"
        ></div>
      </div>
      <p class="text-sm text-gray-600">{{ form.progress.percentage }}% uploaded</p>
    </div>
    
    <button 
      type="submit" 
      :disabled="form.processing"
      class="btn-primary"
    >
      {{ form.processing ? 'Creating...' : 'Create Listing' }}
    </button>
    
    <!-- Show unsaved changes warning -->
    <div v-if="form.isDirty" class="text-yellow-600 text-sm">
      You have unsaved changes
    </div>
  </form>
</template>
```

### Form Methods and Properties

#### Form Methods
```javascript
// Submit methods
form.get(url, options)
form.post(url, options)
form.put(url, options)
form.patch(url, options)
form.delete(url, options)

// Form management
form.reset()                    // Reset to default values
form.reset('field1', 'field2')  // Reset specific fields
form.clearErrors()              // Clear all errors
form.clearErrors('field1')      // Clear specific field errors
form.resetAndClearErrors()      // Reset and clear errors
form.cancel()                   // Cancel current submission

// Error handling
form.setError('field', 'Error message')
form.setError({
  field1: 'Error 1',
  field2: 'Error 2'
})

// Default values
form.defaults()                 // Set current values as defaults
form.defaults('field', 'value') // Set specific field default
```

#### Form Properties
```javascript
form.data           // Current form data
form.errors         // Validation errors
form.processing     // Is form being submitted
form.progress       // Upload progress (for file uploads)
form.hasErrors      // Has any errors
form.isDirty        // Has unsaved changes
form.wasSuccessful  // Last submission was successful
form.recentlySuccessful // Was successful in last 2 seconds
```

## 🔄 State Management

### Preserve State
```vue
<script setup>
import { router } from '@inertiajs/vue3'

// Preserve component state (useful for forms)
router.get('/listings', { search: 'kart' }, { 
  preserveState: true 
})

// Preserve state only on errors
router.post('/listings', data, { 
  preserveState: 'errors' 
})

// Conditional state preservation
router.post('/listings', data, {
  preserveState: (page) => page.props.hasErrors
})
</script>
```

### Preserve Scroll
```vue
<script setup>
import { router } from '@inertiajs/vue3'

// Preserve scroll position
router.get('/listings', { page: 2 }, { 
  preserveScroll: true 
})

// Preserve scroll only on errors
router.post('/listings', data, { 
  preserveScroll: 'errors' 
})

// Conditional scroll preservation
router.post('/listings', data, {
  preserveScroll: (page) => page.props.validationErrors
})
</script>
```

## 📄 Page Components

### Basic Page Structure
```vue
<!-- resources/js/pages/Listings/Index.vue -->
<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
  listings: Array,
  filters: Object,
  pagination: Object
})
</script>

<template>
  <AppLayout>
    <Head title="Listings" />
    
    <div class="container mx-auto px-4 py-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Karting Listings</h1>
        <Link 
          href="/listings/create" 
          class="btn-primary"
        >
          Create New Listing
        </Link>
      </div>
      
      <!-- Filters -->
      <div class="mb-6">
        <form @submit.prevent="submitFilters">
          <div class="flex gap-4">
            <input 
              type="text" 
              v-model="filters.search"
              placeholder="Search listings..."
              class="form-input"
            />
            <select v-model="filters.category" class="form-select">
              <option value="">All Categories</option>
              <option value="go_kart">Go Karts</option>
              <option value="parts">Parts</option>
              <option value="accessories">Accessories</option>
              <option value="consumables">Consumables</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
          </div>
        </form>
      </div>
      
      <!-- Listings Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="listing in listings" 
          :key="listing.id"
          class="bg-white rounded-lg shadow-md overflow-hidden"
        >
          <img 
            :src="listing.image_path || '/images/placeholder.jpg'" 
            :alt="listing.title"
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h3 class="text-xl font-semibold mb-2">{{ listing.title }}</h3>
            <p class="text-gray-600 mb-2">{{ listing.description }}</p>
            <div class="flex justify-between items-center">
              <span class="text-2xl font-bold text-green-600">
                {{ listing.price }} {{ listing.currency }}
              </span>
              <Link 
                :href="`/listings/${listing.id}`"
                class="btn-primary"
              >
                View Details
              </Link>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Pagination -->
      <div class="mt-8">
        <!-- Pagination component here -->
      </div>
    </div>
  </AppLayout>
</template>
```

### Page with Form
```vue
<!-- resources/js/pages/Listings/Create.vue -->
<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const form = useForm({
  title: '',
  description: '',
  category: 'go_kart',
  condition: 'used',
  price: '',
  currency: 'EUR',
  country: '',
  city: '',
  image: null
})

const submit = () => {
  form.post('/listings', {
    onSuccess: () => {
      // Redirect handled by Laravel
    }
  })
}
</script>

<template>
  <AppLayout>
    <Head title="Create Listing" />
    
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-8">Create New Listing</h1>
      
      <form @submit.prevent="submit" class="max-w-2xl">
        <!-- Form fields here -->
        <div class="form-group">
          <label for="title">Title *</label>
          <input 
            id="title"
            type="text" 
            v-model="form.title" 
            :class="{ 'border-red-500': form.errors.title }"
            class="form-input"
            required
          />
          <div v-if="form.errors.title" class="text-red-500 text-sm">
            {{ form.errors.title }}
          </div>
        </div>
        
        <!-- More form fields... -->
        
        <div class="flex gap-4 mt-6">
          <button 
            type="submit" 
            :disabled="form.processing"
            class="btn-primary"
          >
            {{ form.processing ? 'Creating...' : 'Create Listing' }}
          </button>
          <Link href="/listings" class="btn-secondary">
            Cancel
          </Link>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
```

## 🎯 Event Handling

### Global Events
```vue
<script setup>
import { router } from '@inertiajs/vue3'
import { onMounted, onUnmounted } from 'vue'

let removeStartListener
let removeFinishListener

onMounted(() => {
  // Show loading indicator
  removeStartListener = router.on('start', () => {
    console.log('Navigation started')
  })
  
  // Hide loading indicator
  removeFinishListener = router.on('finish', () => {
    console.log('Navigation finished')
  })
})

onUnmounted(() => {
  removeStartListener()
  removeFinishListener()
})
</script>
```

### Per-Visit Events
```vue
<script setup>
import { router } from '@inertiajs/vue3'

const deleteListing = (id) => {
  router.delete(`/listings/${id}`, {
    onBefore: () => confirm('Are you sure you want to delete this listing?'),
    onSuccess: () => {
      // Show success message
      console.log('Listing deleted successfully')
    },
    onError: (errors) => {
      console.log('Error deleting listing:', errors)
    }
  })
}
</script>
```

## 🔧 Advanced Features

### File Uploads
```vue
<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  images: []
})

const handleFileChange = (event) => {
  form.images = Array.from(event.target.files)
}

const submit = () => {
  form.post('/listings', {
    forceFormData: true, // Required for file uploads
    onProgress: (progress) => {
      console.log(`Upload progress: ${progress.percentage}%`)
    }
  })
}
</script>

<template>
  <form @submit.prevent="submit">
    <input 
      type="file" 
      @input="handleFileChange"
      multiple
      accept="image/*"
    />
    
    <!-- Progress bar -->
    <div v-if="form.progress" class="mb-4">
      <div class="w-full bg-gray-200 rounded-full h-2">
        <div 
          class="bg-blue-600 h-2 rounded-full"
          :style="{ width: form.progress.percentage + '%' }"
        ></div>
      </div>
      <p>{{ form.progress.percentage }}% uploaded</p>
    </div>
    
    <button type="submit" :disabled="form.processing">
      Upload
    </button>
  </form>
</template>
```

### Data Transformation
```vue
<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  price: '',
  currency: 'EUR'
})

const submit = () => {
  form
    .transform((data) => ({
      ...data,
      price: parseFloat(data.price),
      currency: data.currency.toUpperCase()
    }))
    .post('/listings')
}
</script>
```

### Conditional Navigation
```vue
<script setup>
import { router } from '@inertiajs/vue3'

const navigateWithConfirmation = (url) => {
  router.visit(url, {
    onBefore: () => {
      if (hasUnsavedChanges.value) {
        return confirm('You have unsaved changes. Are you sure you want to leave?')
      }
      return true
    }
  })
}
</script>
```

## 🎨 Best Practices for BuyKarting.com

### 1. Form Validation
```vue
<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  price: '',
  category: 'go_kart'
})

const submit = () => {
  // Client-side validation
  if (!form.title.trim()) {
    form.setError('title', 'Title is required')
    return
  }
  
  if (!form.price || form.price <= 0) {
    form.setError('price', 'Price must be greater than 0')
    return
  }
  
  // Submit to server
  form.post('/listings')
}
</script>
```

### 2. Loading States
```vue
<template>
  <div>
    <!-- Loading overlay -->
    <div v-if="form.processing" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
        <p class="mt-2">Creating listing...</p>
      </div>
    </div>
    
    <!-- Form content -->
    <form @submit.prevent="submit">
      <!-- Form fields -->
    </form>
  </div>
</template>
```

### 3. Error Handling
```vue
<template>
  <div>
    <!-- Global error message -->
    <div v-if="form.hasErrors" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      <strong>Please fix the following errors:</strong>
      <ul class="list-disc list-inside mt-2">
        <li v-for="(error, field) in form.errors" :key="field">
          {{ error }}
        </li>
      </ul>
    </div>
    
    <!-- Form fields with individual error display -->
    <div class="form-group">
      <input 
        type="text" 
        v-model="form.title"
        :class="{ 'border-red-500': form.errors.title }"
        class="form-input"
      />
      <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">
        {{ form.errors.title }}
      </div>
    </div>
  </div>
</template>
```

### 4. Success Messages
```vue
<template>
  <div>
    <!-- Success message -->
    <div v-if="form.recentlySuccessful" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      Listing created successfully!
    </div>
    
    <!-- Form content -->
  </div>
</template>
```

## 📚 Additional Resources

- [Inertia.js Official Documentation](https://inertiajs.com/)
- [Laravel Inertia Adapter](https://github.com/inertiajs/inertia-laravel)
- [Vue 3 Inertia Adapter](https://github.com/inertiajs/inertia-vue3)
- [Laravel Wayfinder](https://github.com/laravel/wayfinder) - For type-safe routing

## 🚀 Quick Reference

### Common Patterns
```javascript
// Navigation
router.visit('/listings')
router.post('/listings', data)
router.delete('/listings/1')

// Form handling
const form = useForm({ title: '', price: '' })
form.post('/listings')

// State preservation
{ preserveState: true, preserveScroll: true }

// Event handling
router.on('start', () => {})
router.on('finish', () => {})
```

### Form Component Props
```vue
<Form
  action="/listings"
  method="post"
  :transform="transformData"
  :invalidate-cache-tags="['listings']"
>
  <!-- Form content -->
</Form>
```

This documentation covers the essential Inertia.js features you'll need for building the buykarting.com marketplace. The examples are tailored to your project's specific needs with karting equipment listings, user management, and subscription handling.
