<script setup>
import { ref } from 'vue';

const emit = defineEmits(['confirm', 'cancel']);

const show = ref(false);
const title = ref('');
const message = ref('');
const confirmText = ref('Confirm');
const cancelText = ref('Cancel');
const loading = ref(false);

const open = (options = {}) => {
    title.value = options.title || 'Confirm Action';
    message.value = options.message || 'Are you sure?';
    confirmText.value = options.confirmText || 'Confirm';
    cancelText.value = options.cancelText || 'Cancel';
    loading.value = false;
    show.value = true;
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
};

const close = () => {
    show.value = false;
    document.body.style.overflow = '';
};

const handleConfirm = async () => {
    emit('confirm');
    close();
};

const handleCancel = () => {
    emit('cancel');
    close();
};

defineExpose({
    open,
    close
});
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <!-- Backdrop with explicit rgba for opacity -->
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="handleCancel"></div>
        
        <!-- Modal -->
        <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full p-6 z-[10000]">
            <div class="flex items-start">
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        {{ title }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">
                        {{ message }}
                    </p>
                </div>
                <button @click="handleCancel" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button
                    @click="handleCancel"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ cancelText }}
                </button>
                <button
                    @click="handleConfirm"
                    :disabled="loading"
                    class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ loading ? 'Processing...' : confirmText }}
                </button>
            </div>
        </div>
    </div>
</template>
