<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Form;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController as BaseAbstractController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

abstract class AbstractCrudController extends BaseAbstractController {
    public function configureCrud(Crud $crud): Crud
    {
        $crud = parent::configureCrud($crud);
        $crud
            ->setDateFormat('dd/MM/Y')
            ->hideNullValues()
            ->setPageTitle('index','Liste des %entity_label_plural%')
            ->setPageTitle('detail', static function ($entity): string {
                $returnString = '';

                if ($entity instanceof User) {
                    $returnString = sprintf('Utilisateur: <b>%s</b>', $entity->__toString());
                }
                elseif ($entity instanceof Form) {
                    $returnString = sprintf('Formulaire: <b>%s</b>', $entity->__toString());
                }
                elseif ($entity instanceof Category) {
                    $returnString = sprintf('Catégorie: <b>%s</b>', $entity->__toString());
                }

                return $returnString;
            })
            ->setPageTitle('edit', static function ($entity): string {
                $returnString ='';

                if ($entity instanceof User) {
                    $returnString = sprintf('Mise à jour utilisateur: <b>%s</b>', $entity->__toString());
                }
                elseif ($entity instanceof Form) {
                    $returnString = sprintf('Mise à jour du formulaire: <b>%s</b>', $entity->__toString());
                }
                elseif ($entity instanceof Category) {
                    $returnString = sprintf('Mise à jour du catégorie: <b>%s</b>', $entity->__toString());
                }

                return $returnString;
            });

        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash');
            })
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fa fa-eye');
            })
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Ajouter');
            });
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(
                DateTimeFilter::new('createdAt', 'Créé le')
                    ->setFormTypeOption('value_type_options.widget', 'single_text')
                    ->setFormTypeOption('value_type_options.html5', false)
                    ->setFormTypeOption('value_type_options.format', 'dd/MM/yyyy')
                    ->setFormTypeOption('value_type_options.attr.class', 'datepicker')
                )
            ;
    }
}
