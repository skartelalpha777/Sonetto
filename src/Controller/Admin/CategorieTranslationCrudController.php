<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\CategorieTranslation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategorieTranslationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CategorieTranslation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Traduction')
            ->setEntityLabelInPlural('Traductions de catégories')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('categorie', 'Catégorie')
                ->setFormType(EntityType::class)
                ->setFormTypeOptions([
                    'class' => Categorie::class,
                    'choice_label' => 'nom',
                    'placeholder' => '-- Choisir une catégorie --',
                ]),
            ChoiceField::new('locale', 'Langue')->setChoices([
                'Anglais' => 'en',
                'Néerlandais' => 'nl',
                'Espagnol' => 'es',
            ]),
            TextField::new('nom', 'Nom traduit'),
        ];
    }
}
