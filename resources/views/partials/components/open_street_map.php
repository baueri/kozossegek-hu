<div id="map"></div>
<style>#map { height: 500px; }</style>
<script>
    $(() => {
        const markers = JSON.parse("{{ addslashes($markers) }}");
        const map = L.map('map');
        const markerBounds = [];

        const icon = L.icon({
            iconUrl: 'https://cdn4.iconfinder.com/data/icons/essentials-72/24/025_-_Location-48.png',
            iconSize: [28,28],
            popupAnchor: [0, -16]
        });

        map.attributionControl.setPrefix('');
        L.tileLayer('https://tile-c.openstreetmap.fr/hot/{z}/{x}/{y}.png').addTo(map);

        markers.map(marker => {
            const m = L.marker([marker.lat, marker.lon], { icon }).addTo(map);
            m.bindPopup(marker.popup_html.replace(/\\(.)/mg, "$1"));
            markerBounds.push(m);
        });

        const group = new L.featureGroup(markerBounds);
        map.fitBounds(group.getBounds());
    })
</script>