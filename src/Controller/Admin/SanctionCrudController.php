<?php
namespace App\Controller\Admin;

use App\Entity\Sanction;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;                                                                  

class SanctionCrudController extends AbstractCrudController
{
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW); // 👈 ceci désactive le bouton "Créer Sanction"
    }

    public static function getEntityFqcn(): string
    {
        return Sanction::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user'),
            AssociationField::new('argument'),
            TextareaField::new('reason'),
        ];
    }

    
}
