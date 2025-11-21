<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg shadow-sm">
            <p class="font-bold">Success</p>
            <p>{{ session('message') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg shadow-sm">
            <p class="font-bold">Error</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div x-data="{
        calendar: null,
        events: @js($this->getEvents(app(\App\Services\AvailabilityService::class))),
        isModalOpen: false,
        selectedDate: '',
        reason: '',

        init() {
            let calendarEl = this.$refs.calendar;
            this.calendar = new window.Calendar(calendarEl, {
                plugins: [ window.dayGridPlugin, window.interactionPlugin, window.timeGridPlugin ],
                initialView: 'dayGridMonth',
                selectable: true,
                events: this.events,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                dateClick: (info) => {
                    this.selectedDate = info.dateStr;
                    this.reason = '';
                    this.isModalOpen = true;
                },
                eventClick: (info) => {
                    if (info.event.extendedProps.type === 'availability') {
                        if (confirm('Do you want to unblock this date?')) {
                            this.$wire.unblockDate(info.event.extendedProps.availability_id);
                        }
                    } else {
                         // Booking
                         alert('This date has a confirmed booking and cannot be modified here.');
                    }
                }
            });
            this.calendar.render();

            this.$wire.on('calendar-updated', (data) => {
                this.calendar.removeAllEvents();
                this.calendar.addEventSource(data.events);
            });
        },

        saveBlock() {
            if (!this.selectedDate) return;
            this.$wire.blockDate(this.selectedDate, this.reason);
            this.isModalOpen = false;
        }
    }">
        <!-- Calendar Container -->
        <div class="bg-white p-4 rounded shadow">
             <div x-ref="calendar" wire:ignore></div>
        </div>

        <!-- Modal -->
        <div x-show="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h3 class="text-lg font-bold mb-4">Block Date</h3>
                <p class="mb-4 text-gray-600">Mark <span x-text="selectedDate" class="font-semibold text-gray-900"></span> as unavailable.</p>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Reason (Optional)</label>
                    <input type="text" x-model="reason" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. Vacation">
                </div>

                <div class="flex justify-end space-x-2">
                    <button @click="isModalOpen = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                    <button @click="saveBlock()" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Block Date</button>
                </div>
            </div>
        </div>
    </div>
</div>
