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
            TextareaField::new('pointsForts', 'Points forts')
                ->hideOnIndex()
                ->setHelp('Une phrase par ligne (coche verte). Si rempli, remplace l\'affichage des champs techniques sur la carte produit.'),
            TextField::new('resolution', 'Résolution')->hideOnIndex(),
            TextField::new('autonomie', 'Autonomie')->hideOnIndex(),
            TextField::new('stockage', 'Stockage')->hideOnIndex(),
            ChoiceField::new('alimentation', 'Alimentation')->setChoices([
                'Sur batterie' => 'Batterie',
                'Filaire (raccordée)' => 'Filaire',
                'Batterie ou filaire' => 'Les deux',
            ]),
            BooleanField::new('actif', 'Actif (visible sur le site)'),
            AssociationField::new('categorie', 'Catégorie'),
            AssociationField::new('images', 'Photos de la galerie')->hideOnForm()->onlyOnDetail(),
        ];
    }
}
