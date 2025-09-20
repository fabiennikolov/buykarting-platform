<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

// Developer tools state
const showDevTools = ref(false);
const currentTime = ref(new Date().toLocaleTimeString());
const systemInfo = ref({
    php: '8.3.24',
    laravel: '12.30.1',
    node: '18+',
    database: 'SQLite'
});

// Update time every second
onMounted(() => {
    setInterval(() => {
        currentTime.value = new Date().toLocaleTimeString();
    }, 1000);
});

// Quick commands
const quickCommands = [
    { name: 'Start Dev Server', command: 'composer run dev', description: 'Start all development services' },
    { name: 'Run Tests', command: 'php artisan test', description: 'Run the test suite' },
    { name: 'Format Code', command: './scripts/code-quality.sh', description: 'Format and lint code' },
    { name: 'Quick Setup', command: './scripts/quick-setup.sh', description: 'One-command setup' },
    { name: 'Create Model', command: 'php artisan make:model Listing -m', description: 'Create model with migration' },
    { name: 'Create Controller', command: 'php artisan make:controller ListingController', description: 'Create controller' },
];

// Documentation links
const docs = [
    { name: 'Project Overview', file: 'PROJECT_OVERVIEW.md', description: 'Complete project documentation' },
    { name: 'Developer Setup', file: 'DEVELOPER_SETUP.md', description: 'Development environment guide' },
    { name: 'Inertia.js Docs', file: 'INERTIA_DOCUMENTATION.md', description: 'Inertia.js reference' },
    { name: 'Laravel Docs', url: 'https://laravel.com/docs/12.x', description: 'Official Laravel documentation' },
    { name: 'Vue 3 Docs', url: 'https://vuejs.org/guide/', description: 'Vue 3 documentation' },
    { name: 'Tailwind CSS', url: 'https://tailwindcss.com/docs', description: 'Tailwind CSS v4 docs' },
];

// Project structure
const projectStructure = [
    { path: 'app/Models/', description: 'Eloquent models' },
    { path: 'app/Http/Controllers/', description: 'Application controllers' },
    { path: 'resources/js/pages/', description: 'Inertia.js pages' },
    { path: 'resources/js/components/', description: 'Vue components' },
    { path: 'database/migrations/', description: 'Database migrations' },
    { path: 'tests/', description: 'Pest tests' },
];

// Copy command to clipboard
const copyCommand = (command: string) => {
    navigator.clipboard.writeText(command);
    // You could add a toast notification here
};
</script>

<template>
    <Head title="BuyKarting.com - Developer Dashboard">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
        <!-- Header -->
        <header class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">🏁</span>
                            </div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">BuyKarting.com</h1>
                        </div>
                        <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span>Development Environment</span>
                        </div>
                    </div>
                    
                    <nav class="flex items-center space-x-4">
                        <button 
                            @click="showDevTools = !showDevTools"
                            class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                        >
                            {{ showDevTools ? 'Hide' : 'Show' }} Dev Tools
                        </button>
                        
                        <Link
                            v-if="$page.props.auth.user"
                            :href="dashboard()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                :href="login()"
                                class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                            >
                                Log in
                            </Link>
                            <Link
                                :href="register()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Welcome Section -->
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Welcome to BuyKarting.com Development
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                    Your karting equipment marketplace built with Laravel 12, Vue 3, and Inertia.js
                </p>
                <div class="flex justify-center items-center space-x-6 text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span>{{ currentTime }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        <span>PHP {{ systemInfo.php }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                        <span>Laravel {{ systemInfo.laravel }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <!-- Quick Commands -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <span class="mr-2">⚡</span>
                        Quick Commands
                    </h3>
                    <div class="space-y-3">
                        <div 
                            v-for="cmd in quickCommands" 
                            :key="cmd.name"
                            class="group cursor-pointer"
                            @click="copyCommand(cmd.command)"
                        >
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm">{{ cmd.name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ cmd.description }}</p>
                                </div>
                                <span class="text-xs text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300">Click to copy</span>
                            </div>
                            <code class="block mt-1 text-xs text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 p-2 rounded font-mono">{{ cmd.command }}</code>
                        </div>
                    </div>
                </div>

                <!-- Documentation -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <span class="mr-2">📚</span>
                        Documentation
                    </h3>
                    <div class="space-y-3">
                        <a 
                            v-for="doc in docs" 
                            :key="doc.name"
                            :href="doc.url || '#'"
                            class="block p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                        >
                            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ doc.name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ doc.description }}</p>
                        </a>
                    </div>
                </div>

                <!-- Project Structure -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <span class="mr-2">📁</span>
                        Project Structure
                    </h3>
                    <div class="space-y-2">
                        <div 
                            v-for="item in projectStructure" 
                            :key="item.path"
                            class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded"
                        >
                            <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">{{ item.path }}</code>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ item.description }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Development Status -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="mr-2">🚀</span>
                    Development Status
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">✅</div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Environment</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ready</p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">🔄</div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Models</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pending</p>
                    </div>
                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">⚙️</div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Controllers</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pending</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">🎨</div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Frontend</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ready</p>
                    </div>
                </div>
            </div>

            <!-- Developer Tools (Collapsible) -->
            <div v-if="showDevTools" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="mr-2">🛠️</span>
                    Developer Tools
                </h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- System Information -->
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">System Information</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                <span class="text-sm text-gray-600 dark:text-gray-400">PHP Version</span>
                                <code class="text-sm text-blue-600 dark:text-blue-400">{{ systemInfo.php }}</code>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Laravel Version</span>
                                <code class="text-sm text-blue-600 dark:text-blue-400">{{ systemInfo.laravel }}</code>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Node.js</span>
                                <code class="text-sm text-blue-600 dark:text-blue-400">{{ systemInfo.node }}</code>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Database</span>
                                <code class="text-sm text-blue-600 dark:text-blue-400">{{ systemInfo.database }}</code>
                            </div>
                        </div>
                    </div>

                    <!-- Useful Links -->
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Useful Links</h4>
                        <div class="space-y-2">
                            <a href="https://laravel.com/docs/12.x" target="_blank" class="block p-2 bg-gray-50 dark:bg-gray-700 rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Laravel Documentation</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Official Laravel 12 docs</p>
                            </a>
                            <a href="https://inertiajs.com/" target="_blank" class="block p-2 bg-gray-50 dark:bg-gray-700 rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Inertia.js</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Modern monolith approach</p>
                            </a>
                            <a href="https://vuejs.org/guide/" target="_blank" class="block p-2 bg-gray-50 dark:bg-gray-700 rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Vue 3 Guide</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Vue.js documentation</p>
                            </a>
                            <a href="https://tailwindcss.com/docs" target="_blank" class="block p-2 bg-gray-50 dark:bg-gray-700 rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Tailwind CSS</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Utility-first CSS framework</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Getting Started -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-8 text-white">
                <h3 class="text-2xl font-bold mb-4">Ready to Start Building?</h3>
                <p class="text-blue-100 mb-6">
                    Follow the development roadmap in PROJECT_OVERVIEW.md to build the karting marketplace step by step.
                </p>
                <div class="flex flex-wrap gap-4">
                    <button 
                        @click="copyCommand('composer run dev')"
                        class="px-6 py-3 bg-white text-blue-600 rounded-lg font-medium hover:bg-gray-100 transition-colors"
                    >
                        Start Development Server
                    </button>
                    <button 
                        @click="copyCommand('php artisan make:model Listing -m')"
                        class="px-6 py-3 bg-blue-700 text-white rounded-lg font-medium hover:bg-blue-800 transition-colors"
                    >
                        Create First Model
                    </button>
                    <button 
                        @click="copyCommand('php artisan test')"
                        class="px-6 py-3 bg-blue-700 text-white rounded-lg font-medium hover:bg-blue-800 transition-colors"
                    >
                        Run Tests
                    </button>
                </div>
            </div>

            <!-- Claude AI Integration -->
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="mr-2">🤖</span>
                    Claude AI Integration
                </h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    This project is optimized for Claude AI assistance. Here are some useful prompts you can use:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Model Creation</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">"Create a Listing model with all the fields from PROJECT_OVERVIEW.md"</p>
                        <button 
                            @click="copyCommand('Create a Listing model with all the fields from PROJECT_OVERVIEW.md')"
                            class="text-xs text-blue-600 dark:text-blue-400 hover:underline"
                        >
                            Copy prompt
                        </button>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Controller Creation</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">"Create a ListingController with CRUD operations for the karting marketplace"</p>
                        <button 
                            @click="copyCommand('Create a ListingController with CRUD operations for the karting marketplace')"
                            class="text-xs text-blue-600 dark:text-blue-400 hover:underline"
                        >
                            Copy prompt
                        </button>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Frontend Components</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">"Create a listing card component for the karting marketplace"</p>
                        <button 
                            @click="copyCommand('Create a listing card component for the karting marketplace')"
                            class="text-xs text-blue-600 dark:text-blue-400 hover:underline"
                        >
                            Copy prompt
                        </button>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Testing</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">"Create Pest tests for the Listing model and controller"</p>
                        <button 
                            @click="copyCommand('Create Pest tests for the Listing model and controller')"
                            class="text-xs text-blue-600 dark:text-blue-400 hover:underline"
                        >
                            Copy prompt
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
