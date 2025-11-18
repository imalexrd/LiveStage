# Google Maps API Integration Guide

This document provides a comprehensive guide to setting up and using the Google Maps API within this project. Following these steps is crucial for the location-based features to function correctly.

## 1. Core Component: `LocationPickerModal`

The primary integration point for Google Maps is the reusable Livewire component `LocationPickerModal`. This component provides a map-based interface for selecting a location and is used in:
-   **Musician Search:** To filter musicians by proximity.
-   **Booking Form:** To set the event location.
-   **Musician Profile:** To set the musician's base location.

## 2. API Key and Configuration

The integration requires a Google Maps API key and a Map ID.

### 2.1. Environment Variables

The following variables must be set in your `.env` file:

```env
GOOGLE_MAPS_API_KEY=your-google-maps-api-key
GOOGLE_MAPS_MAP_ID=your-google-maps-map-id
```

-   `GOOGLE_MAPS_API_KEY`: Your personal API key from the Google Cloud Console.
-   `GOOGLE_MAPS_MAP_ID`: A unique ID for your map style, required for using `AdvancedMarkerElement`.

### 2.2. Creating a Map ID

To create a Map ID:
1.  Go to the Google Cloud Console.
2.  Navigate to **Map Management**.
3.  Click **Create New Map ID**.
4.  Give it a name (e.g., "LiveStage Map").
5.  Select **Vector** as the **Map type**. This is mandatory for `AdvancedMarkerElement`.
6.  Copy the generated ID and add it to your `.env` file.

## 3. Required APIs

For the map and all its features to work, you must enable the following APIs in the Google Cloud Console for your project:

1.  **Maps JavaScript API:** The core service for displaying the map.
2.  **Places API:** Used for the address search and autocomplete functionality.
3.  **Geocoding API:** Used to convert coordinates (from a click on the map) into a human-readable address.

To enable them, search for each API by name in the console and click the "Enable" button.

## 4. Frontend Implementation Details

The `LocationPickerModal` uses modern Google Maps features to provide a smooth user experience.

### 4.1. Dynamic Script Loading

To prevent race conditions and improve performance, the Google Maps script is loaded dynamically only when the modal is opened for the first time. This is handled by a helper function in `resources/views/livewire/location-picker-modal.blade.php`.

### 4.2. Modern APIs

The implementation has been updated to use the latest Google Maps APIs to avoid deprecation warnings and ensure future compatibility:
-   **`google.maps.marker.AdvancedMarkerElement`** is used instead of the old `google.maps.Marker`.
-   **`<gmp-place-autocomplete>`** (a Web Component) is used instead of the old `google.maps.places.Autocomplete`.

This ensures the integration is stable, performant, and aligned with Google's best practices.
