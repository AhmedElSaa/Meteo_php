<?php require "view_begin.php"; ?>

<div class="container">
    <section class="bulletin closable">
    <div class="bulletin-top">
        <div class="bulletin-top-left">
            <h2><?= htmlspecialchars($bulletin['sujet']) ?></h2>
            <p class="bulletin-date">
                <?= date('d/m/Y H:i', strtotime($bulletin['date'])) ?>
            </p>
        </div>
        <button class="close-btn" aria-label="Fermer">✕</button>
    </div>
    
    <?php if (!empty($bulletin['introduction'])): ?>
        <p><?= htmlspecialchars($bulletin['introduction']) ?></p>
    <?php endif; ?>

    <?php if (!empty($bulletin['situation'])): ?>
        <p><strong>Situation :</strong> <?= htmlspecialchars($bulletin['situation']) ?></p>
    <?php endif; ?>

    <?php if (!empty($bulletin['previsions_html'])): ?>
        <div class="bulletin-previsions">
        <?= $bulletin['previsions_html'] ?>
        </div>
    <?php endif; ?>
    </section>
    <div class="map-top">
        <div id="map"></div>
    </div>
    <div class="panel-bot">
        <h1 class="panel-title">Météo — <span id="panel-city" class="muted">Aucune ville sélectionnée</span></h1>
        <div id="panel">
            <p class="muted">Cliquer un marqueur sur la carte pour afficher la météo du jour et les prévisions ici.</p>
        </div>
    </div>
</div>

<script src="Content/script.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDURJiPyw23aapTGjZMQsCH4ooqUE65XOQ&callback=initMap" async defer></script>

<?php require "view_end.php"; ?>