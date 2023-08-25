
<p>Ce projet est une app de monitoring d'objet connecté. la simulation des devices est faite en générant automatiquement des données.</p>

<h2>RUN</h2>
<p>
    Pour demarrer : <br/>
    <ul>
        <li>S'assurer d'avoir docker et docker-compose ou docker desktop installée </li>
        <li>Se placer dans le répertoire monitor-app</li>
        <li>Taper la commande " sail up -d " ou "docker-compose up -d"</li>
        <li>docker exec -it app php artisan schedule:work </li>
    </ul>
</p>
