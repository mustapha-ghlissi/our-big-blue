<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud = parent::configureCrud($crud);

        return $crud->setEntityLabelInPlural('utilisateurs')
            ->setPageTitle('new', 'Nouveau compte utilisateur');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email', 'Nom d\'utilisateur / Adresse email'),
            TextField::new('firstName', 'Prénom')->hideOnIndex(),
            TextField::new('lastName', 'Nom')->hideOnIndex(),
            TextField::new('fullName', 'Nom & Prénom')->hideOnForm()->hideOnDetail(),
            TextField::new('password', 'Mot de passe')
                ->setFormType(PasswordType::class)->onlyOnForms()
            ,
            TextField::new('phoneNumber', 'Tél'),
            DateField::new('createdAt', 'Créé le')->hideOnForm(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters = parent::configureFilters($filters);

        return $filters
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('phoneNumber')
            ;
    }
}
