<?php require "view_begin.php"; ?>

<div class="container">
    <div class="map-left">
        <div id="map"></div>
    </div>
    <div class="panel-right">
        <h1>Météo — <span id="panel-city" class="muted">Aucune ville sélectionnée</span></h1>
        <div id="panel">
            <p class="muted">Cliquer un marqueur sur la carte pour afficher la météo du jour et les prévisions ici.</p>
        </div>
    </div>
</div>

<script src="Content/script.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDURJiPyw23aapTGjZMQsCH4ooqUE65XOQ&callback=initMap" async defer></script>

<?php require "view_end.php"; ?>