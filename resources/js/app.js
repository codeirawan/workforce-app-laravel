import L from 'leaflet';
import { GeoSearchControl, OpenStreetMapProvider } from 'leaflet-geosearch';

'use strict';

function createDetailMap(options) {
    var defaults = {
        mapId: 'detailMap',
        mapZoom: 16,
        mapCenter: [-6.3146971, 107.3025469],
        markerShow: true,
        markerPosition: [-6.3146971, 107.3025469],
        circleShow: false,
        circleColour: '#4E66F8',
        circleFill: '#8798fa',
        circleOpacity: .5,
        circleRadius: 500,
        circlePosition: [-6.3146971, 107.3025469],
    };

    var settings = $.extend({}, defaults, options);

    var dragging = false,
        tap = false;

    if ($(window).width() > 700) {
        dragging = true;
        tap = true;
    }

    var detailMap = L.map(settings.mapId, {
        center: settings.mapCenter,
        zoom: settings.mapZoom,
        dragging: dragging,
        tap: tap,
        scrollWheelZoom: false
    });

    detailMap.once('focus', function () {
        detailMap.scrollWheelZoom.enable();
    });

    L.tileLayer('https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}{r}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://wikimediafoundation.org/wiki/Maps_Terms_of_Use">Wikimedia maps</a>',
        minZoom: 1,
        maxZoom: 19
    }).addTo(detailMap);

    if (settings.circleShow) {
        L.circle(settings.circlePosition, {
            color: settings.circleColour,
            weight: 1,
            fillColor: settings.circleFill,
            fillOpacity: settings.circleOpacity,
            radius: settings.circleRadius
        }).addTo(detailMap);
    }

    if (settings.markerShow) {
        L.marker(settings.markerPosition).addTo(detailMap);
    }

    if (settings.locate) {
        var marker = L.marker(settings.markerPosition, {
            draggable: true
        }).addTo(detailMap);

        marker.on('dragend', function(e) {
            $('#lokasi').val(marker.getLatLng());
        });

        var onLocationFound = function(e) {
            marker.setLatLng(e.latlng);
            $('#lokasi').val(e.latlng);
        };

        detailMap.on('locationfound', onLocationFound);

        detailMap.locate({
            setView: true,
        });
    }

    const provider = new OpenStreetMapProvider();

    const searchControl = new GeoSearchControl({
        autoClose: true,
        provider: provider,
        searchLabel: 'Masukkan alamat',
        showMarker: false,
    });

    detailMap.addControl(searchControl);

    detailMap.on('geosearch/showlocation', function(e) {
        var latlng = L.latLng(e.marker._latlng.lat, e.marker._latlng.lng)
        marker.setLatLng(latlng);
        $('#lokasi').val(latlng);
    });
}

createDetailMap({
    mapId: 'map',
    mapZoom: 19,
    markerShow: false,
    locate: true
});