// Demande une confirmation avant les actions qui modifient l'état d'un élément.
document.querySelectorAll('[data-confirmation]').forEach(function(lien) {
    lien.addEventListener('click', function(event) {
        if (!confirm(lien.getAttribute('data-confirmation'))) {
            event.preventDefault();
        }
    });
});
