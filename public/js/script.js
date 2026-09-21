/**
 * Sonetto — script commun à toutes les pages publiques
 * - Le menu mobile (hamburger) est géré nativement par le JS de Bootstrap
 *   (data-bs-toggle="collapse"), rien à coder ici pour ça.
 * - Ce fichier gère uniquement ce que Bootstrap ne fait pas :
 *   le filtre par catégorie sur la page Produits, et le formulaire newsletter.
 */
document.addEventListener('DOMContentLoaded', function () {
    initFiltresProduits();
    initNewsletter();
});

/**
 * Filtre les cartes produits (page produits.html.twig) sans recharger la page.
 * Chaque bouton .filtre-btn porte un attribut data-filtre="<slug catégorie>" ou "tous".
 * Chaque carte .produit-card-col porte un attribut data-categorie="<slug catégorie>".
 */
function initFiltresProduits() {
    const boutons = document.querySelectorAll('.filtre-btn');
    const cartes = document.querySelectorAll('.produit-card-col');

    if (boutons.length === 0 || cartes.length === 0) {
        return;
    }

    boutons.forEach(function (bouton) {
        bouton.addEventListener('click', function () {
            const filtre = bouton.dataset.filtre;

            boutons.forEach(function (b) {
                b.classList.remove('active');
            });
            bouton.classList.add('active');

            cartes.forEach(function (carte) {
                const correspond = filtre === 'tous' || carte.dataset.categorie === filtre;
                carte.style.display = correspond ? '' : 'none';
            });
        });
    });
}

