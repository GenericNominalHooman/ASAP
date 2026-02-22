<div>
    @if ($message)
        <div x-data="{ show: true }" x-show="show"
            x-init="setTimeout(() => show = false, 5000); $watch('show', value => { if(!value) setTimeout(() => $wire.clearMessage(), 500) })"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 sm:scale-100"
            x-transition:leave-end="opacity-0 sm:scale-95"
            class="fixed bottom-6 right-6 z-[9999] flex items-center p-4 mb-4 text-white {{ $type === 'error' ? 'bg-rose-600 border-rose-500 shadow-rose-500/30' : 'bg-emerald-600 border-emerald-500 shadow-emerald-500/30' }} border rounded-xl shadow-2xl ring-1 ring-white/10"
            role="alert">
            <div class="flex items-center justify-center shrink-0 w-8 h-8 rounded-lg {{ $type === 'error' ? 'bg-rose-700' : 'bg-emerald-700' }} text-white me-3">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 20 20">
                    @if($type === 'error')
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                            clip-rule="evenodd" />
                    @else
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                            clip-rule="evenodd" />
                    @endif
                </svg>
            </div>
            <div class="ms-3 text-sm font-medium pr-8">
                <span class="font-bold">{{ $type === 'error' ? 'Error' : 'Success' }}</span>: {{ $message }}
            </div>
            <button @click="show = false" type="button"
                class="ms-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 focus:ring-white p-1.5 inline-flex items-center justify-center h-8 w-8 text-white/70 hover:text-white hover:bg-white/10 transition-colors"
                aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    @endif
</div>