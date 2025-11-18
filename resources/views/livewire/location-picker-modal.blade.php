<div>
    <div
        x-data="{
            show: @entangle('show'),
            map: null,
            marker: null,
            geocoder: null,
            mapInitialized: false,
            init() {
                this.$watch('show', (value) => {
                    if (value && !this.mapInitialized) {
                        loadGoogleMaps(() => {
                            this.initMap();
                            this.mapInitialized = true;
                        });
                    }
                });
            },
            async initMap() {
                const { Map } = await google.maps.importLibrary('maps');
                const { AdvancedMarkerElement } = await google.maps.importLibrary('marker');
                const { PlaceAutocompleteElement } = await google.maps.importLibrary('places');

                this.map = new Map(document.getElementById('map'), {
                    center: { lat: -34.397, lng: 150.644 },
                    zoom: 8,
                    mapId: '{{ config('services.google.maps_map_id') }}',
                });

                this.geocoder = new google.maps.Geocoder();

                this.marker = new AdvancedMarkerElement({
                    map: this.map,
                    position: { lat: -34.397, lng: 150.644 },
                    gmpDraggable: true,
                });

                this.map.addListener('click', (e) => {
                    this.updateMarkerPosition(e.latLng);
                    this.geocodePosition(e.latLng);
                });

                this.marker.addListener('dragend', (e) => {
                    this.geocodePosition(e.latLng);
                });

                const autocomplete = new PlaceAutocompleteElement();
                const pacInput = document.getElementById('pac-input');
                pacInput.appendChild(autocomplete);


                autocomplete.addEventListener('gmp-placechange', () => {
                    const place = autocomplete.place;
                    if (!place.geometry || !place.geometry.location) {
                        return;
                    }

                    if (place.geometry.viewport) {
                        this.map.fitBounds(place.geometry.viewport);
                    } else {
                        this.map.setCenter(place.geometry.location);
                        this.map.setZoom(17);
                    }

                    this.updateMarkerPosition(place.geometry.location);
                    this.geocodePosition(place.geometry.location);
                });
            },
            updateMarkerPosition(latLng) {
                this.marker.position = latLng;
            },
            geocodePosition(latLng) {
                this.geocoder.geocode({ latLng: latLng }, (results, status) => {
                    if (status === 'OK' && results[0]) {
                        $wire.set('address', results[0].formatted_address);
                        $wire.set('latitude', results[0].geometry.location.lat());
                        $wire.set('longitude', results[0].geometry.location.lng());
                    }
                });
            }
        }"
        x-init="init()"
        x-show="show"
        @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
            >
                <div class="flex items-start justify-between">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                        Select Location
                    </h3>
                    <button type="button" @click="show = false" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>

                <div class="mt-4">
                    <div id="pac-input"></div>
                    <div id="map" style="height: 400px;"></div>
                </div>

                <div class="mt-4">
                    <p><strong>Selected Address:</strong> <span x-text="$wire.address"></span></p>
                    <p><strong>Latitude:</strong> <span x-text="$wire.latitude"></span></p>
                    <p><strong>Longitude:</strong> <span x-t ext="$wire.longitude"></span></p>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button @click="show = false">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                    <x-primary-button class="ml-3" @click="$wire.selectLocation()">
                        {{ __('Select Location') }}
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function loadGoogleMaps(callback) {
            if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                callback();
                return;
            }
            if (window.googleMapsLoading) {
                window.addEventListener('google-maps-loaded', callback);
                return;
            }
            window.googleMapsLoading = true;
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places,marker`;
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
            script.onload = () => {
                window.googleMapsLoading = false;
                window.dispatchEvent(new Event('google-maps-loaded'));
                callback();
            };
        }
    </script>
</div>
