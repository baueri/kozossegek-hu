<div id="map" class="lazy"></div>
<style>#map { height: {{ $height }}px; }</style>
<script id="osm-script">
    const osmMap = document.getElementById("map");
    osmMap.addEventListener("ElementLazyLoaded", function () {
        const leafletScript = document.createElement("script");
        leafletScript.src = "https://unpkg.com/leaflet@1.7.1/dist/leaflet.js";
        leafletScript.integrity = "sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==";
        leafletScript.crossOrigin = "crossorigin";
        osmMap.after(leafletScript);

        leafletScript.onload = function () {
            const markers = JSON.parse("{{ addslashes($markers) }}");
            const map = L.map('map');

            map.attributionControl.setPrefix('');
            L.tileLayer('https://tile-c.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
                minZoom: 7
            }).addTo(map);

            map.fitBounds([[45.74, 16.11], [48.58, 22.90]]);

            markers.forEach(marker => {
                const lat = parseFloat(marker.lat);
                const lon = parseFloat(marker.lon);
                if (!Number.isFinite(lat) || !Number.isFinite(lon)) {
                    return;
                }
                const icon = L.icon({
                    iconUrl: marker.marker,
                    iconSize: [28, 28],
                    popupAnchor: [0, -16]
                });
                const m = L.marker([lat, lon], {icon}).addTo(map);
                m.bindPopup(marker.popup_html.replace(/\\(.)/mg, "$1"));
            });
        }
    })
</script>
