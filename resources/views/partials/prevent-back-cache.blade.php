<script>
    // Empêche l'affichage d'une page protégée depuis le cache navigateur (bouton Retour)
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
</script>
