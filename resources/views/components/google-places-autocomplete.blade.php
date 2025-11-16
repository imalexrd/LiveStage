<div wire:ignore>
    <input id="address" type="text" placeholder="Enter a location" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
</div>

@push('scripts')
<script>
    function initAutocomplete() {
        const addressInput = document.getElementById('address');
        const autocomplete = new google.maps.places.Autocomplete(addressInput, {
            types: ['geocode'],
        });

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                console.log("No details available for input: '" + place.name + "'");
                return;
            }

            @this.set('location_address', place.formatted_address);
            @this.set('location_latitude', place.geometry.location.lat());
            @this.set('location_longitude', place.geometry.location.lng());
        });
    }

    if (window.google && window.google.maps) {
        initAutocomplete();
    } else {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initAutocomplete`;
        script.async = true;
        document.head.appendChild(script);
    }
</script>
@endpush
