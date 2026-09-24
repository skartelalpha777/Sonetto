<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Entity\ProduitTranslation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProduitTranslationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProduitTranslation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Traduction')
            ->setEntityLabelInPlural('Traductions de produits')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('produit', 'Produit')
                ->setFormType(EntityType::class)
                ->setFormTypeOptions([
                    'class' => Produit::class,
                    'choice_label' => 'nom',
                    'placeholder' => '-- Choisir un produit --',
                ]),
            ChoiceField::new('locale', 'Langue')->setChoices([
                'Anglais' => 'en',
                'Néerlandais' => 'nl',
                'Espagnol' => 'es',
            ]),
            TextField::new('sousTitre', 'Sous-titre traduit'),
            TextareaField::new('description', 'Description traduite'),
            TextareaField::new('pointsForts', 'Points forts traduits (une phrase par ligne)'),
            TextareaField::new('pointsFortsDetail', 'Points forts détail traduits (une phrase par ligne)'),
            TextareaField::new('avertissement', 'Avertissement traduit'),
        ];
    }
}
