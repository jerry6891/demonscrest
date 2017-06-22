
//----------------------------------------------------------
//                          Google Map
//-----------------------------------------------------------
function pixflow_googleMap(id, lat, lon, zoom, type, icon) {
    "use strict";
    if ($(".md-google-map").length) {

        if (type == 'gray') {
            $("." + id).gmap3({
                map: {
                    options: {
                        zoom: parseInt(zoom),
                        disableDefaultUI: true, //  disabling zoom in touch devices
                        disableDoubleClickZoom: true, //  disabling zoom by double click on map
                        center: new google.maps.LatLng(lat, lon),
                        draggable: true, //  disable map dragging
                        mapTypeControl: true,
                        navigationControl: false,
                        scrollwheel: false,
                        streetViewControl: false,
                        panControl: false,
                        zoomControl: true,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        mapTypeControlOptions: {
                            mapTypeIds: [google.maps.MapTypeId.ROADMAP, "Gray"]
                        }
                    }
                },
                styledmaptype: {
                    id: "Gray",
                    options: {
                        name: "Gray"
                    },
                    styles: [
                        {
                            featureType: "water",
                            elementType: "geometry",
                            stylers: [
                                {color: "#1d1d1d"}
                            ]
                        }, {
                            featureType: "landscape",
                            stylers: [
                                {color: "#3e3e3e"},
                                {lightness: 7}
                            ]
                        }, {
                            featureType: "administrative.country",
                            elementType: "geometry.stroke",
                            stylers: [
                                {color: "#5f5f5f"},
                                {weight: 1}
                            ]
                        }, {
                            featureType: "landscape.natural.terrain",
                            stylers: [
                                {color: "#4f4f4f"}
                            ]
                        }, {
                            featureType: "road",
                            stylers: [
                                {color: "#393939"}
                            ]
                        }, {
                            featureType: "administrative.country",
                            elementType: "labels",
                            stylers: [
                                {visibility: "on"},
                                {weight: 0.4},
                                {color: "#686868"}
                            ]
                        }, {
                            eatureType: "administrative.locality",
                            elementType: "labels.text.fill",
                            stylers: [
                                {weigh: 2.4},
                                {color: "#9b9b9b"}
                            ]
                        }, {
                            featureType: "administrative.locality",
                            elementType: "labels.text",
                            stylers: [
                                {visibility: "on"},
                                {lightness: -80}
                            ]
                        }, {
                            featureType: "poi",
                            stylers: [
                                {visibility: "off"},
                                {color: "#d78080"}
                            ]
                        }, {
                            featureType: "administrative.province",
                            elementType: "geometry",
                            stylers: [
                                {visibility: "on"},
                                {lightness: -80}
                            ]
                        }, {
                            featureType: "water",
                            elementType: "labels",
                            stylers: [
                                {color: "#adadad"},
                                {weight: 0.1}
                            ]
                        }, {
                            featureType: "administrative.province",
                            elementType: "labels.text.fill",
                            stylers: [
                                {color: "#3a3a3a"},
                                {weight: 4.8},
                                {lightness: -69}
                            ]
                        }

                    ]
                },
                marker: {
                    values: [{
                        'latLng': [lat, lon]
                    }],
                    options: {
                        icon: new google.maps.MarkerImage(icon, new google.maps.Size(80, 60, "px", "px"))
                    }
                }

            });
            $('.' + id).gmap3('get').setMapTypeId("Gray");//Display Gray Map On Load  if we don't have this line map loads in default
            if ($(window).width() <= 1280) {
                $("." + id).gmap3("get").setOptions({draggable: false});
            }

        } else {
            $("." + id).gmap3({
                map: {
                    options: {
                        zoom: parseInt(zoom),
                        disableDefaultUI: true, //  disabling zoom in touch devices
                        disableDoubleClickZoom: true, //  disabling zoom by double click on map
                        center: new google.maps.LatLng(lat, lon),
                        draggable: false, //  disable map dragging
                        mapTypeControl: true,
                        navigationControl: false,
                        scrollwheel: false,
                        streetViewControl: false,
                        panControl: false,
                        zoomControl: false,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                    }
                },
                marker: {
                    values: [{
                        'latLng': [lat, lon]
                    }],
                    options: {
                        icon: new google.maps.MarkerImage(icon, new google.maps.Size(80, 60, "px", "px"))
                    }
                }
            });

        }
    }

}