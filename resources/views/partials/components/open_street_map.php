<div id="map"></div>
<style>#map { height: {{ $height }}px; }</style>
<script>
    $(() => {
        const markers = JSON.parse("{{ addslashes($markers) }}");
        const map = L.map('map');
        const markerBounds = [];

        map.attributionControl.setPrefix('');
        L.tileLayer('https://tile-c.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
            minZoom: 7
        }).addTo(map);

        markers.map(marker => {
            const icon = L.icon({
                iconUrl: marker.marker,
                iconSize: [28,28],
                popupAnchor: [0, -16]
            });
            const m = L.marker([marker.lat, marker.lon], { icon }).addTo(map);
            m.bindPopup(marker.popup_html.replace(/\\(.)/mg, "$1"));
            markerBounds.push(m);
        });

        const group = new L.featureGroup(markerBounds);
        map.fitBounds(group.getBounds());
    })
</script>