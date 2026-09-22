<?php

namespace App\Controller\Admin;

use App\Entity\ImageProduit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ImageProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ImageProduit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ImageField::new('nomFichier', 'Photo')
                ->setBasePath('images/produits')
                ->setUploadDir('public/images/produits'),
            BooleanField::new('isMain', 'Principale')
                ->setHelp('Photo affichée sur la carte produit (une seule par produit).'),
            IntegerField::new('position', 'Ordre d\'affichage'),
            AssociationField::new('produit', 'Produit'),
        ];
    }
}
