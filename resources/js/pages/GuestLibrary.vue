<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header with Navigation -->
        <div class="bg-white dark:bg-gray-800 shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button
                        @click="goBack"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Guest Library</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Manage all guests and their bookings</p>
                    </div>
                </div>
                <button
                    @click="showCreateModal = true"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    + Add Guest
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Search & Filter -->
            <div class="mb-6 flex gap-4">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search guests by name or email..."
                    class="flex-1 px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
                <select
                    v-model="sortBy"
                    class="px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                    <option value="name">Sort by Name</option>
                    <option value="email">Sort by Email</option>
                    <option value="created_at">Sort by Created</option>
                </select>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="flex justify-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4 mb-6">
                <p class="text-red-600 dark:text-red-400">{{ error }}</p>
                <button @click="fetchGuests" class="mt-2 text-sm text-red-500 hover:underline">Retry</button>
            </div>

            <!-- Guests Grid -->
            <div v-else-if="filteredGuests.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                    v-for="guest in filteredGuests"
                    :key="guest.id"
                    class="border dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition"
                >
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-white">{{ guest.name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ guest.email }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-500">{{ guest.phone }}</p>
                        </div>
                        <button
                            @click="toggleAttachMenu(guest)"
                            class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full hover:bg-green-200 transition"
                            title="Manage bookings"
                        >
                            {{ guest.bookings?.length || 0 }} booking(s)
                        </button>
                    </div>

                    <!-- Current Bookings List -->
                    <div v-if="guest.bookings?.length" class="mt-3 mb-3">
                        <div v-for="booking in guest.bookings" :key="booking.id" class="text-xs bg-gray-100 dark:bg-gray-700 rounded px-2 py-1 mb-1 flex justify-between items-center">
                            <span>Booking #{{ booking.id }} ({{ formatDate(booking.pivot?.created_at) }})</span>
                            <button
                                @click="detachFromBooking(guest, booking.id)"
                                class="text-red-500 hover:text-red-700 text-xs font-bold"
                                title="Remove from booking"
                            >×</button>
                        </div>
                    </div>
                    <div v-else class="mt-3 mb-3 text-xs text-gray-500">
                        Not attached to any bookings
                    </div>

                    <div class="text-xs text-gray-500 dark:text-gray-500 mb-3">
                        Added: {{ new Date(guest.created_at).toLocaleDateString() }}
                    </div>
                    <div class="flex gap-2">
                        <button
                            @click="editGuest(guest)"
                            class="text-sm text-blue-600 hover:underline dark:text-blue-400"
                        >
                            Edit
                        </button>
                        <button
                            @click="confirmDelete(guest)"
                            class="text-sm text-red-600 hover:underline dark:text-red-400"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12 text-gray-500 dark:text-gray-400">
                No guests found. Add one to get started.
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div v-if="showCreateModal || editingGuest" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
                <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">
                    {{ editingGuest ? 'Edit Guest' : 'Add New Guest' }}
                </h2>
                <form @submit.prevent="editingGuest ? updateGuest() : createGuest()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full px-3 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            class="w-full px-3 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            class="w-full px-3 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>
                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:underline"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            {{ editingGuest ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation -->
        <div v-if="deletingGuest" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm w-full mx-4">
                <h2 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Delete Guest</h2>
                <p class="mb-4 text-gray-600 dark:text-gray-300">
                    Are you sure you want to delete <strong>{{ deletingGuest.name }}</strong>? This will also remove them from all bookings.
                </p>
                <div class="flex justify-end gap-3">
                    <button
                        @click="deletingGuest = null"
                        class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:underline"
                    >
                        Cancel
                    </button>
                    <button
                        @click="deleteGuest"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Attach Booking Menu -->
        <div v-if="attachGuest" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm w-full mx-4">
                <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">
                    Attach {{ attachGuest.name }} to Booking
                </h2>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Booking</label>
                    <select
                        v-model="selectedBookingId"
                        class="w-full px-3 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">-- Choose a booking --</option>
                        <option v-for="booking in availableBookings" :key="booking.id" :value="booking.id">
                            Booking #{{ booking.id }} ({{ formatDate(booking.checkin_at) }} - {{ formatDate(booking.checkout_at) }})
                        </option>
                    </select>
                    <p v-if="availableBookings.length === 0" class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        No available bookings. All bookings already have this guest or there are no bookings.
                    </p>
                </div>
                <div class="flex justify-end gap-3">
                    <button
                        @click="attachGuest = null; selectedBookingId = ''"
                        class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:underline"
                    >
                        Cancel
                    </button>
                    <button
                        @click="attachToBooking"
                        :disabled="!selectedBookingId || !availableBookings.length"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Attach
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const guests = ref([])
const bookings = ref([])
const search = ref('')
const sortBy = ref('name')
const showCreateModal = ref(false)
const editingGuest = ref(null)
const deletingGuest = ref(null)
const attachGuest = ref(null)
const selectedBookingId = ref('')
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
    await fetchAllData()
})

const fetchAllData = async () => {
    loading.value = true
    error.value = null
    try {
        await Promise.all([fetchGuests(), fetchBookings()])
    } catch (err) {
        console.error('Failed to load data:', err)
        error.value = 'Failed to load data. Please try again.'
    } finally {
        loading.value = false
    }
}

const fetchGuests = async () => {
    try {
        const res = await fetch('/api/guests')
        if (!res.ok) throw new Error('Failed to fetch guests')
        guests.value = await res.json()
    } catch (err) {
        console.error('Failed to fetch guests:', err)
        throw err
    }
}

const fetchBookings = async () => {
    try {
        const res = await fetch('/api/bookings')
        if (!res.ok) throw new Error('Failed to fetch bookings')
        bookings.value = await res.json()
    } catch (err) {
        console.error('Failed to fetch bookings:', err)
        throw err
    }
}

// Bookings that this guest is NOT already attached to
const availableBookings = computed(() => {
    if (!attachGuest.value) return []
    const guestId = attachGuest.value.id
    const attachedIds = new Set(attachGuest.value.bookings?.map(b => b.id) || [])
    return bookings.value.filter(b => !attachedIds.has(b.id))
})

const filteredGuests = computed(() => {
    let result = guests.value
    if (search.value) {
        const term = search.value.toLowerCase()
        result = result.filter(g =>
            g.name.toLowerCase().includes(term) ||
            g.email.toLowerCase().includes(term)
        )
    }
    return result.sort((a, b) => {
        if (sortBy.value === 'name') return a.name.localeCompare(b.name)
        if (sortBy.value === 'email') return a.email.localeCompare(b.email)
        if (sortBy.value === 'created_at') return new Date(b.created_at) - new Date(a.created_at)
        return 0
    })
})

const formatDate = (date) => {
    if (!date) return ''
    return new Date(date).toLocaleDateString()
}

const goBack = () => {
    // Use Inertia to go back in history, or fallback to home
    if (window.history.length > 1) {
        router.back()
    } else {
        router.visit('/')
    }
}

const editGuest = (guest) => {
    editingGuest.value = guest
    form.value = {
        name: guest.name,
        email: guest.email,
        phone: guest.phone || ''
    }
}

const closeModal = () => {
    showCreateModal.value = false
    editingGuest.value = null
    form.value = { name: '', email: '', phone: '' }
}

const form = ref({ name: '', email: '', phone: '' })

const createGuest = async () => {
    try {
        const res = await fetch('/api/guests', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(form.value)
        })
        if (res.ok) {
            await fetchGuests()
            closeModal()
        } else {
            const data = await res.json()
            console.error('Create failed:', data)
        }
    } catch (err) {
        console.error('Failed to create guest:', err)
    }
}

const updateGuest = async () => {
    try {
        const res = await fetch(`/api/guests/${editingGuest.value.id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(form.value)
        })
        if (res.ok) {
            await fetchGuests()
            closeModal()
        } else {
            const data = await res.json()
            console.error('Update failed:', data)
        }
    } catch (err) {
        console.error('Failed to update guest:', err)
    }
}

const confirmDelete = (guest) => {
    deletingGuest.value = guest
}

const deleteGuest = async () => {
    try {
        const res = await fetch(`/api/guests/${deletingGuest.value.id}`, {
            method: 'DELETE'
        })
        if (res.ok) {
            await fetchGuests()
            deletingGuest.value = null
        }
    } catch (err) {
        console.error('Failed to delete guest:', err)
    }
}

// Booking attachment management
const toggleAttachMenu = (guest) => {
    attachGuest.value = guest
    selectedBookingId.value = ''
}

const attachToBooking = async () => {
    if (!selectedBookingId.value) return
    try {
        const res = await fetch(`/api/guests/${attachGuest.value.id}/bookings/${selectedBookingId.value}`, {
            method: 'POST'
        })
        if (res.ok) {
            await fetchGuests()
            attachGuest.value = null
            selectedBookingId.value = ''
        }
    } catch (err) {
        console.error('Failed to attach guest to booking:', err)
    }
}

const detachFromBooking = async (guest, bookingId) => {
    if (!confirm(`Remove guest from booking #${bookingId}?`)) return
    try {
        const res = await fetch(`/api/guests/${guest.id}/bookings/${bookingId}`, {
            method: 'DELETE'
        })
        if (res.ok) {
            await fetchGuests()
        }
    } catch (err) {
        console.error('Failed to detach guest from booking:', err)
    }
}
</script>