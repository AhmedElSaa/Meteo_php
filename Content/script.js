const ICONS = {
  "02": "Image/nuage.png",
  "03": "Image/soleil.png",
  "12": "Image/nuage.png",
  "14": "Image/soleil.png",
  "15": "Image/pluie.png",
  "17": "Image/pluie.png",
  "18": "Image/pluie.png",
  "default": "Image/nuage.png"
};
function iconForCode(code) {
  const c = String(code);
  return ICONS[c] || ICONS.default;
}

let map, infoWindow;

/**
* Création de la carte avec des paramètres précis.
*/
function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: -21.1151, lng: 55.5364 }, // Centre de La Réunion
    zoom: 10,
    mapId: "d255976cebb081ca24b4dd18",       // Id de la carte
    mapTypeControl: false,                   // Enlève la vue satellite
    streetViewControl: false,                // Enlève Street View
  });

  infoWindow = new google.maps.InfoWindow();
  loadCoordsAndMarkers();
}

/**
* Crée un google.maps.Marker, puis récupère le temps du jour pour définir l’icône.
*/
function createMarker(ville, coord) {
  const marker = new google.maps.Marker({
    position: coord,
    map: map,
    title: ville,
    icon: { url: ICONS.default, scaledSize: new google.maps.Size(34, 34), anchor: new google.maps.Point(17, 17) }
  });

  prefetchCity(ville)
    .then(data => {
      const url = iconForCode(data?.today?.temps_code);
      marker.setIcon({ url, scaledSize: new google.maps.Size(24, 24), anchor: new google.maps.Point(17, 17) });
    })
    .catch(console.error);

  marker.addListener("click", () => {
    dataCity(ville, marker);
  });
}

/** 
* Précharge les données par ville dans le cache.
*/
const CITY_CACHE = {};
function prefetchCity(ville) {
  if (CITY_CACHE[ville]) return Promise.resolve(CITY_CACHE[ville]);
  const url = "index.php?controller=home&action=ville&name=" + encodeURIComponent(ville);
  return fetch(url)
    .then(r => r.json())
    .then(data => {
      if (data.error) throw new Error(data.error);
      CITY_CACHE[ville] = data;
      return data;
    });
}

/**
 * Va chercher toutes les positions dans coords.json et les places.
 */
function loadCoordsAndMarkers() {
  fetch("index.php?controller=home&action=coords")
    .then((r) => r.json())
    .then((data) => {
      if (!data || !data.coords) throw new Error("Réponse coords invalide.");
      Object.entries(data.coords).forEach(([ville, coord]) => {
        createMarker(ville, coord);
      });
    })
    .catch((err) => {
      console.error(err);
    });
}

/**
* Charge les données de la ville et les affiches.
*/
function dataCity(ville, marker) {
  const title = document.getElementById("panel-city");
  const panel = document.getElementById("panel");
  if (title) title.textContent = ville;
  if (panel) panel.innerHTML = `<p class="muted">Chargement des prévisions…</p>`;

  infoWindow.setContent(
    `<div><strong>${ville}</strong><div class="muted">Chargement…</div></div>`
  );
  infoWindow.open(map, marker);

  prefetchCity(ville)
    .then(data => {
      infoWindow.setContent(infoWindowContent(ville, data.today));
      renderForecastPanel(data.previsions);
    })
    .catch(err => {
      console.error(err);
      infoWindow.setContent(`<div style="color:#c00">Erreur de chargement</div>`);
    });
}

/** 
* Contenu HTML de la bulle (météo du jour).
*/
function infoWindowContent(ville, today) {
  return `
    <div class="infobulle">
      <div class="infobulle-txt">${ville}</div>
      <div class="infobulle-desc">
        <img src="Image/thermometre_black.png" width="20" height="20" alt="">
        <div>↑ ${today.temp_max}°C<br>↓ ${today.temp_min}°C</div>
      </div>
      <div class="infobulle-desc">
        <img src="Image/humidite_black.png" width="20" height="20" alt="">
        <div>${today.humidite}%</div>
      </div>
      <div class="infobulle-desc">
        <img src="Image/vent_black.png" width="20" height="20" alt="">
        <div>${today.vent_force} km/h${today.rafales ? `<br>(Rafales : ${today.rafales} km/h)` : ""}</div>
      </div>
    </div>`;
}


/** 
* Visuel des prévisions.
*/
function renderForecastPanel(forecasts) {
  const panel = document.getElementById("panel");
  const list = (forecasts).map(f => `
    <div class="prevision">
      <div class="date"><strong>${formatDateFR(f.date)}</strong></div>
      <div class="subcategorie">
        <img src="Image/thermometre.png" alt="temp" width="24px" />
        <p>↓ ${f.temp_min}°C - ↑ ${f.temp_max}°C</p>
      </div>
      <div class="subcategorie">
        <img src="Image/vent.png" alt="vent" width="24px" />
        <span class="vent">${f.vent_force} km/h ${f.rafales ? "(↑ " + f.rafales + " km/h)" : ""}<img src="Image/boussole.png" alt="boussole" width="20px" />${f.vent_dir}</span>
      </div>
      <div class="subcategorie">
        <img src="Image/humidite.png" alt="humidite" width="24px" />
        <p>${f.humidite}%</p>
      </div>
      <div class="subcategorie">
        <img src="Image/meteo.png" alt="meteo" width="24px" />
        <p>${labelTemps(f.temps_code)}</p>
      </div>
    </div>
  `).join("");
  panel.innerHTML = list;
}

/** 
* Convertit "-temps" en libellé.
*/
function labelTemps(code) {
  switch (String(code)) {
    case "02": return "Dégagé";
    case "03": return "Ensoleillé";
    case "12": return "Nuageux";
    case "14": return "Éclaircies";
    case "15": return "Pluvieux";
    case "17": return "Averses";
    case "18": return "Orageux";
    default:   return "";
  }
}

/** 
* Formatage de la date. 
*/
function formatDateFR(input) {
  const d = new Date(input);
  return isNaN(d) ? String(input) : d.toLocaleDateString('fr-FR', {
    day: '2-digit', month: '2-digit', year: 'numeric'
  });
}