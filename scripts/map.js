let map = L.map('map').setView([-15.77972, -47.92972], 4);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © OpenStreetMap contributors'
        }).addTo(map);

        let userMarker;
        let routeControl;

        function obterGeolocalizacao() {
            return new Promise((resolve, reject) => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(resolve, reject);
                } else {
                    reject("Geolocalização não é suportada neste navegador.");
                }
            });
        }

        function buscarEndereco() {
            const enderecoDestino = document.getElementById('cep').value.trim();

            if (!enderecoDestino) {
                alert("Por favor, digite um endereço de destino.");
                return;
            }

            obterGeolocalizacao().then(position => {
                const userLat = position.coords.latitude;
                const userLon = position.coords.longitude;

                if (userMarker) {
                    map.removeLayer(userMarker);
                }

                userMarker = L.marker([userLat, userLon]).addTo(map)
                    .bindPopup("Você está aqui!")
                    .openPopup();

                map.setView([userLat, userLon], 14);

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(enderecoDestino)}`)
                    .then(res => res.json())
                    .then(locations => {
                        if (locations.length > 0) {
                            const destino = locations[0];
                            const destinoLat = destino.lat;
                            const destinoLon = destino.lon;

                            L.marker([destinoLat, destinoLon]).addTo(map)
                                .bindPopup(`Destino: ${enderecoDestino}`)
                                .openPopup();

                            if (routeControl) {
                                routeControl.setWaypoints([L.latLng(userLat, userLon), L.latLng(destinoLat, destinoLon)]);
                            } else {
                                routeControl = L.Routing.control({
                                    waypoints: [
                                        L.latLng(userLat, userLon), 
                                        L.latLng(destinoLat, destinoLon)
                                    ],
                                    createMarker: function() { return null; }
                                }).addTo(map);
                            }
                        } else {
                            alert("Destino não encontrado.");
                        }
                    })
                    .catch(() => alert("Erro ao buscar o destino."));
            }).catch(err => {
                alert("Erro ao obter a geolocalização: " + err.message);
            });
        }


