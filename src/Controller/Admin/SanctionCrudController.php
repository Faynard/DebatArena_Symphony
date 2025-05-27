<?php
namespace App\Controller\Admin;

use App\Entity\Sanction;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class SanctionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sanction::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user'),
            AssociationField::new('argument'),
            DateField::new('sanctionDate'),
            TextareaField::new('reason'),
        ];
    }
}
