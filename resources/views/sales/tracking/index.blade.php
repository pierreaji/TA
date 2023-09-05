@extends('layouts.app')
@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="form-group my-3">
                <label>Choose Sales</label>
                <select class="select2 form-sales form-select form-select-lg" data-allow-clear="true" required>
                    <option value="" selected disabled>Filter Sales</option>
                    @foreach ($sales as $item)
                        <option value="{{ $item->id }}" {{ $firstSales->id == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="map" style="height: 400px;"></div>
            <div class="pb-20">
                <div class="card-datatable table-responsive">
                    <table class="dt-row-grouping table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Track At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-nowrap">
                            @foreach ($tracking as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="table-plus">{{ $item->User->name }}</td>
                                    <td>{{ $item->latitude }}</td>
                                    <td>{{ $item->longitude }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <a href="{{ route('sales.tracking.index', ['sales' => $firstSales->id, 'track' => $item->id]) }}"
                                            class="btn btn-sm btn-primary">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $('.form-sales').on('change', function() {
            window.location.replace(
                `{{ route('sales.tracking.index') }}?sales=${$('.form-sales').val()}&track={{ $firstTrack?->id }}`
            )
        })

        function initMap(position) {
            const myLatLng = {
                lat: parseFloat("{{ $firstTrack?->latitude }}"),
                lng: parseFloat("{{ $firstTrack?->longitude }}")
            };

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: myLatLng,
            });

            var routeCoordinates = [];
            let routes = @json($tracking);
            routes.map((row, index) => {
                routeCoordinates.push({
                    lat: parseFloat(row.latitude),
                    lng: parseFloat(row.longitude)
                })
            })
            var routePath = new google.maps.Polyline({
                path: routeCoordinates,
                geodesic: true,
                strokeColor: '#FF0000', // adjust line colour
                strokeOpacity: 1.0, // adjust line opacity  
                strokeWeight: 2 // adjust line thickness
            });

            routePath.setMap(map);


            var marker = new google.maps.Marker({
                position: myLatLng,
                map,
            });

            marker.addListener("click", () => {
                window.open(
                    `https://www.google.co.id/maps/search/${myLatLng.lat},${myLatLng.lng}/@${myLatLng.lat},${myLatLng.lng},17z?entry=ttu`,
                    '_blank' // <- This is what makes it open in a new window.
                );
            });

            setInterval(() => {
                let lat = parseFloat("{{ $firstTrack?->latitude }}");
                let long = parseFloat("{{ $firstTrack?->longitude }}");

                $.ajax({
                    url: "{{ url('map') }}?id_user=" + $('.form-sales').val(),
                    type: "GET"
                }).then((resp) => {
                    lat = parseFloat(resp.data.latitude);
                    long = parseFloat(resp.data.longitude);

                    const myLatLng = {
                        lat: lat,
                        lng: long
                    };

                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 15,
                        center: myLatLng,
                    });

                    var routeCoordinates = [];
                    let routes = resp.tracks;
                    routes.map((row, index) => {
                        routeCoordinates.push({
                            lat: parseFloat(row.latitude),
                            lng: parseFloat(row.longitude)
                        })
                    })
                    var routePath = new google.maps.Polyline({
                        path: routeCoordinates,
                        geodesic: true,
                        strokeColor: '#FF0000', // adjust line colour
                        strokeOpacity: 1.0, // adjust line opacity  
                        strokeWeight: 2 // adjust line thickness
                    });

                    routePath.setMap(map);


                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        map,
                    });

                    marker.addListener("click", () => {
                        window.open(
                            `https://www.google.co.id/maps/search/${myLatLng.lat},${myLatLng.lng}/@${myLatLng.lat},${myLatLng.lng},17z?entry=ttu`,
                            '_blank' // <- This is what makes it open in a new window.
                        );
                    });
                })
            }, 10000);
        }
    </script>

    <script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY', 'AIzaSyCWjSQkpYWRMa93lsB6UbQ8jeEWtH7J43s') }}&callback=initMap">
    </script>
@endsection
