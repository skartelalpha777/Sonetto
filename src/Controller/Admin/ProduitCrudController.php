<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom', 'Nom'),
            TextField::new('sousTitre', 'Sous-titre')
                ->hideOnIndex()
                ->setHelp('Laisser vide pour afficher automatiquement "Caméra {résolution}".'),
            TextareaField::new('description', 'Description')->hideOnIndex(),
            MoneyField::new('prix', 'Prix')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),
            ImageField::new('imagePrincipale', 'Photo principale')
                ->setBasePath('images/produits')
                ->hideOnForm()
                ->setHelp('Gérée depuis "Photos de la galerie" ci-dessous : coche "Principale" sur une photo pour qu\'elle devienne celle-ci.'),
            TextareaField::new('pointsForts', 'Points forts (carte + fiche détail)')
                ->hideOnIndex()
                ->setHelp('Une phrase par ligne (coche verte). Affichés sur la carte produit ET en premier sur la fiche détail. Si rempli, remplace l\'affichage des champs techniques.'),
            TextareaField::new('pointsFortsDetail', 'Points forts (fiche détail uniquement)')
                ->hideOnIndex()
                ->setHelp('Une phrase par ligne. Si rempli, remplace complètement la liste ci-dessus sur la fiche détaillée (la carte produit garde, elle, la liste "Points forts"). Laisser vide pour que la fiche détail affiche la même liste que la carte.'),
            TextareaField::new('avertissement', 'Avertissement (fiche détail uniquement)')
                ->hideOnIndex()
                ->setHelp('Affiché dans un encart orange juste après la description, sur la fiche détaillée uniquement. Laisser vide si aucun avertissement n\'est nécessaire.'),
            TextField::new('resolution', 'Résolution')->hideOnIndex(),
            TextField::new('autonomie', 'Autonomie')->hideOnIndex(),
            TextField::new('stockage', 'Stockage')->hideOnIndex(),
            ChoiceField::new('alimentation', 'Alimentation')->setChoices([
                'Sur batterie' => 'Batterie',
                'Filaire (raccordée)' => 'Filaire',
                'Batterie ou filaire' => 'Les deux',
            ]),
            BooleanField::new('actif', 'Actif (visible sur le site)'),
            BooleanField::new('aVenir', 'Produit à venir')
                ->setHelp('Affiche un badge "Produit à venir" sur la carte et la fiche détail.'),
            AssociationField::new('categorie', 'Catégorie'),
            AssociationField::new('images', 'Photos de la galerie')->hideOnForm()->onlyOnDetail(),
        ];
    }
}
