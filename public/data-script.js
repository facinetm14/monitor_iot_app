
/**
 * rafraichissement de la page à chaque minute
 * interval est defini à 1min 1s car la frequence de simulation
 * des données est d'une minute, la marge est juste par precaution.
*/
const interval = 61 * 1000
setInterval(function () {
    window.location.reload(true);
}, interval);
