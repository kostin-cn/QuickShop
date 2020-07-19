/**
 * Created by Hexagen on 24.11.2016.
 */
jQuery.noConflict();
(function($){
    $(document).ready(function() {

    // координаты карты
    // let oz_sale = new google.maps.LatLng(55.72154973720068,37.63575106859207);

    function initialize() {
        const MY_MAPTYPE_ID = 'Merzen';
        var map;
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
            gestureHandling: 'cooperative',
            mapTypeControlOptions: {
                mapTypeIds: [MY_MAPTYPE_ID, 'satellite']
            }
        };
        var setCenter = true;

        var polygonPlace = $('.map-delivery-place').data('place');
        // var polygonPlace = [
        //     [
        //         {lat:54.526642, lng:36.158790},
        //         {lat:54.533929, lng:36.152739},
        //         {lat:54.541399, lng:36.164326},
        //         {lat:54.528974, lng:36.246747},
        //         {lat:54.505010, lng:36.228036},
        //         {lat:54.526642, lng:36.158790},
        //     ],
        // ];
        var polygonOptions = {
            fillColor:"#00AAFF",
            fillOpacity:0.2,
            strokeColor:"#FFAA00",
            strokeOpacity:0.8,
            strokeWeight:2,
            clickable:false,
        };
        const featureOpts = [
            {
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    }
                ]
            },
            {
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#616161"
                    }
                ]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    }
                ]
            },
            {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#bdbdbd"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#eeeeee"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#757575"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#e5e5e5"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#ffffff"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#757575"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#dadada"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#616161"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            },
            {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#e5e5e5"
                    }
                ]
            },
            {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#eeeeee"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#c9c9c9"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            }
        ];

        // let styledMapOptions = {
        //     name: 'Бюро 23'
        // };
        var markers = new Array();

        // Display a map on the page
        map = new google.maps.Map(document.getElementById("map"), mapOptions);
        map.setTilt(45);

        // Multiple Markers

        var rest_markers = [];
        var info_windows = [];

        $('.map-this-to-center').each(function () {
            var address = $(this).data('addr');
            var lat = $(this).data('lat');
            var lng = $(this).data('lng');
            var key = $(this).data('key');
            // var place = $(this).data('place');
            var place = polygonPlace[key];
            var marker_type = $(this).data('marker');
            var item_info = [address, lat, lng, marker_type, place];
            rest_markers.push(item_info);
            var item_descr = [
                '<div class="info_content" data-lng="' + lng + '">' +
                // '<img class="image" src="' + address +'" alt="">'+
                    '<span>'+address+'</span>'+
                '</div>'
            ];
            info_windows.push(item_descr);
        });
        let customMapType = new google.maps.StyledMapType(featureOpts, {name: 'Merzen'});
        map.mapTypes.set(MY_MAPTYPE_ID, customMapType);
        map.setMapTypeId(MY_MAPTYPE_ID);

        //icons
        let fillColor = '#1a1e27';
        let icon = {
            path:"M23,0C10.3,0,0,10.5,0,23.4C0,36,21.4,58.3,22.3,59.2L23,60l0.7-0.8C24.7,58.3,46,36,46,23.4C46,10.5,35.7,0,23,0z\n" +
                "\t M32.6,23.8c-1,1.6-2.3,2.9-3.9,3.8S25.1,29,23,29s-4-0.5-5.7-1.4s-3-2.2-4-3.8c-1-1.6-1.4-3.4-1.4-5.5s0.5-3.8,1.4-5.5\n" +
                "\tc0.9-1.6,2.3-2.9,4-3.8s3.6-1.4,5.7-1.4s4,0.5,5.6,1.4s3,2.2,3.9,3.8c1,1.6,1.4,3.5,1.4,5.5S33.5,22.2,32.6,23.8z M30.6,13.9\n" +
                "\tc0.8,1.3,1.1,2.8,1.1,4.5s-0.4,3.1-1.1,4.5s-1.8,2.4-3.2,3.1s-2.8,1-4.4,1s-3.2-0.4-4.5-1.1c-1.4-0.8-2.4-1.8-3.2-3.1\n" +
                "\ts-1.2-2.8-1.2-4.5s0.4-3.1,1.2-4.5s1.8-2.4,3.2-3.1c1.3-0.8,2.9-1.1,4.5-1.1s3.2,0.4,4.5,1.1S29.9,12.5,30.6,13.9z",
            fillColor: fillColor,
            fillOpacity: .9,
            anchor: new google.maps.Point(23, 50),
            strokeWeight: 0,
            scale: 1
        };
        let $icon_o = {
            path:"M23,0C10.3,0,0,10.5,0,23.4C0,36,21.4,58.3,22.3,59.2L23,60l0.7-0.8C24.7,58.3,46,36,46,23.4C46,10.5,35.7,0,23,0z\n" +
                "\t M32.6,23.8c-1,1.6-2.3,2.9-3.9,3.8S25.1,29,23,29s-4-0.5-5.7-1.4s-3-2.2-4-3.8c-1-1.6-1.4-3.4-1.4-5.5s0.5-3.8,1.4-5.5\n" +
                "\tc0.9-1.6,2.3-2.9,4-3.8s3.6-1.4,5.7-1.4s4,0.5,5.6,1.4s3,2.2,3.9,3.8c1,1.6,1.4,3.5,1.4,5.5S33.5,22.2,32.6,23.8z M30.6,13.9\n" +
                "\tc0.8,1.3,1.1,2.8,1.1,4.5s-0.4,3.1-1.1,4.5s-1.8,2.4-3.2,3.1s-2.8,1-4.4,1s-3.2-0.4-4.5-1.1c-1.4-0.8-2.4-1.8-3.2-3.1\n" +
                "\ts-1.2-2.8-1.2-4.5s0.4-3.1,1.2-4.5s1.8-2.4,3.2-3.1c1.3-0.8,2.9-1.1,4.5-1.1s3.2,0.4,4.5,1.1S29.9,12.5,30.6,13.9z",
            fillColor: fillColor,
            fillOpacity: .9,
            anchor: new google.maps.Point(23, 50),
            strokeWeight: 0,
            scale: 1
        };
        let $icon_d = {
            path:"M18.5,9.7h10v17.2H15.7c0.9-0.7,1.6-2.1,2-4.2s0.6-4.7,0.7-7.9L18.5,9.7z M46,23.4C46,36,24.7,58.3,23.7,59.2L23,60\n" +
                "\tl-0.7-0.8C21.4,58.3,0,36,0,23.4C0,10.5,10.3,0,23,0S46,10.5,46,23.4z M33.9,26.9h-3.2V7.8H16.6l-0.2,6.8c-0.1,3.9-0.5,6.9-1,9\n" +
                "\ts-1.5,3.2-2.9,3.3h-1v6.6h2.1v-4.7h18.3v4.7H34L33.9,26.9L33.9,26.9z",
            fillColor: fillColor,
            fillOpacity: .9,
            anchor: new google.maps.Point(23, 50),
            strokeWeight: 0,
            scale: 1
        };
        let $icon_den = {
            path:"M31.3,13c0.8,1.4,1.3,3.1,1.3,5s-0.4,3.5-1.3,5c-0.8,1.4-2,2.6-3.6,3.4c-1.5,0.8-3.3,1.2-5.4,1.2h-6.9V8.5h6.9\n" +
                "\tc2,0,3.8,0.4,5.4,1.2C29.3,10.5,30.5,11.6,31.3,13z M46,23.4C46,36,24.7,58.3,23.7,59.2L23,60l-0.7-0.8C21.4,58.3,0,36,0,23.4\n" +
                "\tC0,10.5,10.3,0,23,0S46,10.5,46,23.4z M35,18c0-2.3-0.5-4.3-1.6-6.1c-1-1.8-2.5-3.1-4.4-4.1c-1.9-1-4.1-1.5-6.5-1.5H13v23.4h9.5\n" +
                "\tc2.5,0,4.6-0.5,6.5-1.5c1.9-1,3.4-2.4,4.4-4.1C34.5,22.3,35,20.3,35,18z",
            fillColor: fillColor,
            fillOpacity: .9,
            anchor: new google.maps.Point(23, 50),
            strokeWeight: 0,
            scale: 1
        };
        let $icon_c = {
            path:"M23,0C10.3,0,0,10.5,0,23.4C0,36,21.4,58.3,22.3,59.2L23,60l0.7-0.8C24.7,58.3,46,36,46,23.4C46,10.5,35.7,0,23,0z M16,22.8\n" +
                "\tc0.8,1.3,1.8,2.4,3.2,3.1c1.4,0.8,2.9,1.1,4.5,1.1c2.5,0,4.6-0.8,6.2-2.5l1.4,1.4c-0.9,1-2,1.8-3.4,2.3c-1.3,0.5-2.8,0.8-4.3,0.8\n" +
                "\tc-2.1,0-4-0.5-5.6-1.4s-3-2.2-3.9-3.8c-1-1.6-1.4-3.5-1.4-5.5s0.5-3.8,1.4-5.5C15,11.2,16.3,9.9,18,9s3.6-1.4,5.6-1.4\n" +
                "\tc1.6,0,3,0.3,4.3,0.8c1.3,0.5,2.4,1.3,3.4,2.3l-1.4,1.4c-1.6-1.7-3.7-2.5-6.2-2.5c-1.7,0-3.2,0.4-4.5,1.1c-1.4,0.8-2.4,1.8-3.2,3.1\n" +
                "\tc-0.8,1.3-1.2,2.8-1.2,4.4S15.2,21.4,16,22.8z",
            fillColor: fillColor,
            fillOpacity: .9,
            anchor: new google.maps.Point(23, 50),
            strokeWeight: 0,
            scale: 1
        };
        let $icon_w = {
            path:"M23,0C10.3,0,0,10.5,0,23.4C0,36,21.4,58.3,22.3,59.2L23,60l0.7-0.8C24.7,58.3,46,36,46,23.4C46,10.5,35.7,0,23,0z\n" +
                "\t M30.6,29.4h-2.1L23,13.3l-5.6,16.1h-2.1L9,10.6h2.1l5.4,16.2l5.6-16.2H24l5.6,16.3l5.5-16.3H37L30.6,29.4z",
            fillColor: fillColor,
            fillOpacity: .9,
            anchor: new google.maps.Point(23, 50),
            strokeWeight: 0,
            scale: 1
        };
        let $icon_empty = {
            path: "M0",
            fillOpacity: 0,
            anchor: new google.maps.Point(0, 0),
            strokeWeight: 0,
            scale: 1
        };

        // Display multiple markers on a map
        var infoWindow = new google.maps.InfoWindow(), marker, i;

        // Loop through our array of markers & place each one on the map
        for( i = 0; i < rest_markers.length; i++ ) {
            var position = new google.maps.LatLng(rest_markers[i][1], rest_markers[i][2]);
            bounds.extend(position);
            polygonOptions.paths = rest_markers[i][4];
            polygonMap = new google.maps.Polygon(polygonOptions);
            polygonMap.setMap(map);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                icon: eval('$'+rest_markers[i][3]),
                // icon: rest_markers[i][3],
                title: ''
            });

            // Allow each marker to have an info window
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infoWindow.setContent(info_windows[i][0]);
                    infoWindow.open(map, marker);
                    // setTimeout(function () {
                    //     var d_lng = $('.gm-style-iw').find('.info_content').data('lng');
                    //     $('.map-this-to-center').removeClass('active');
                    //     $(".map-this-to-center[data-lng='" + d_lng + "']").addClass('active');
                    // }, 500);
                }
            })(marker, i));

            markers.push(marker);
            // Automatically center the map fitting all markers on the screen
            map.fitBounds(bounds);
        }



        // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
            this.setZoom(12);
            google.maps.event.removeListener(boundsListener);
        });
        var typeListener = google.maps.event.addListener((map), 'maptypeid_changed', function(event) {
            fillColor = (map.getMapTypeId() === 'satellite')?'#ffffff':'#1a1e27';
            markers.forEach(function (element) {
                let icon = element.icon;
                icon.fillColor = fillColor;
                element.setIcon(icon);
            });
        });

        let location_index = 0;
        let list = document.querySelectorAll('.map-this-to-center');
        for (let j = 0; j < list.length; ++j){
            list[j].addEventListener('click',function(event){
                [].forEach.call(list, function(el) {
                    el.classList.remove("active");
                });
                this.classList.add('active');
                location_index = j;
                google.maps.event.trigger(markers[location_index], 'click');
                let latLng = markers[location_index].getPosition();
                let zoomLvl = (j === 0) ? 12 : 12;
                map.setCenter(latLng);
                map.setZoom(zoomLvl);

                $("#restAddress").text($(this).data('address'));
                $("#restPhone").text($(this).data('phone')).attr('href', $(this).data('phonehref'));
                $("#restEmail").text($(this).data('email')).attr('href', $(this).data('emailhref'));
                $("#restTime").text($(this).data('time'));
            });
        }

        setTimeout(function () {
            var latLng = markers[0].getPosition();
            google.maps.event.trigger(markers[0], 'click');// returns LatLng object
            map.setCenter(latLng); // setCenter takes a LatLng object
            // map.setZoom(5);
        }, 300);
    }
    if ($('#map').length) {
        initialize();
    }
        $('.cityBtn-rest').click(function () {
            let current = $(this);
            let target = $(this).data('href');
            $.ajax({
                url: target,
                context: $('#rest-list'),
                type: 'GET',
                success: function(data){
                    $(this).html(data);
                },
                complete: function () {
                    $('.cityBtn-rest').removeClass('active');
                    current.addClass('active');
                    initialize();
                }
            });
        });
});
})(jQuery);