function initMap() {
    var scuola = {lat: 45.355930, lng: 9.683006};
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 18,
        center: scuola,
        styles: [{"stylers":[{"hue":"#dd0d0d"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]}],
    });
    var image = 'imgs/pin.png';
    var marker = new google.maps.Marker({
        position: scuola,
        map: map,
        icon: image
    });
}
