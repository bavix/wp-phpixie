'use strict';

var autocomplete = void 0,
    mapInited = false;

var componentForm = {
    street_number: {
        id: 'streetNumber',
        name: 'short_name'
    },
    route: {
        id: 'street',
        name: 'long_name'
    },
    locality: {
        id: 'city',
        name: 'long_name'
    },
    administrative_area_level_1: {
        id: 'state',
        name: 'short_name'
    },
    country: {
        id: 'country',
        name: 'long_name'
    },
    postal_code: {
        id: 'zipCode',
        name: 'short_name'
    }
};

function initAutocomplete() {

    autocomplete = new google.maps.places.SearchBox(document.getElementById('autocomplete'), {
        types: ['geocode']
    });

    autocomplete.addListener('places_changed', fillInAddress);

    var map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: {
            lat: 45.0392674,
            lng: 38.987221
        }
    });
}

function toForm(place) {

    for (var component in componentForm) {
        document.getElementById(componentForm[component].id).value = '';
    }

    if (typeof place.address_components === "undefined") {
        var map = new google.maps.Map(document.getElementById("map"));
        var service = new google.maps.places.PlacesService(map);

        service.getDetails({
            placeId: place.place_id
        }, function (place, status) {
            if (status === google.maps.places.PlacesServiceStatus.OK) {

                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                toForm(place);
            }
        });

        return;
    }

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (typeof componentForm[addressType] !== "undefined") {
            var doc = document.getElementById(componentForm[addressType].id);
            doc.value = place.address_components[i][componentForm[addressType].name];
        }
    }
}

function fillInAddress() {
    'use strict';

    var place = autocomplete.getPlaces()[0];

    var lat = place.geometry.location.lat();
    var lng = place.geometry.location.lng();

    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    toForm(place);

    initMap(lat, lng, place.formatted_address);
}

function initMap(lat, lng, title) {

    'use strict';

    var coords = {
        lat: lat,
        lng: lng
    };

    var map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: coords
    });

    var marker = new google.maps.Marker({
        position: coords,
        draggable: true,
        animation: google.maps.Animation.DROP,
        map: map,
        title: title
    });

    google.maps.event.addDomListener(window, "resize", function () {
        var center = map.getCenter();
        google.maps.event.trigger(map, "resize");
        map.setCenter(center);
    });

    google.maps.event.addListener(marker, 'dragend', function () {

        document.getElementById('latitude').value = marker.position.lat();
        document.getElementById('longitude').value = marker.position.lng();

        var loc = {
            lat: marker.position.lat(),
            lng: marker.position.lng()
        };

        var geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(loc.lat, loc.lng);

        geocoder.geocode({ 'latLng': latlng }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                toForm(results[0]);
            }
        });
    });

    mapInited = true;
}
//# sourceMappingURL=map.js.map