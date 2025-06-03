<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserCrudController extends AbstractCrudController
{

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('pseudo')
                ->setLabel($this->translator->trans('admin.user.pseudo')),
            TextField::new('email')
                ->setLabel($this->translator->trans('admin.user.email')),
            ArrayField::new('roles')
                ->setLabel($this->translator->trans('admin.user.roles'))
                ->formatValue(fn ($v, $entity) =>
                implode(', ', array_map(fn ($role) => $this->translator->trans($this->getRoleLabel($role)), $entity->getRoles()))
                ),
            ChoiceField::new('roles')
                ->setLabel($this->translator->trans('admin.user.roles'))
                ->onlyOnForms()
                ->setLabel('admin.user.roles')
                ->allowMultipleChoices()
                ->setChoices([
                    $this->translator->trans('roles.user') => 'ROLE_USER',
                    $this->translator->trans('roles.moderator') => 'ROLE_MODERATOR',
                    $this->translator->trans('roles.admin') => 'ROLE_ADMIN',
                ])
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $export = Action::new('export', $this->translator->trans('admin.user.export'))
            ->linkToCrudAction('export');
        
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, $export);
    }

    private function getRoleLabel(string $role): string
    {
        return match ($role) {
            'ROLE_USER' => 'roles.user',
            'ROLE_MODERATOR' => 'roles.moderator',
            'ROLE_ADMIN' => 'roles.admin',
            default => $role,
        };
    }

    public function new(AdminContext $context): Response
    {
        return $this->redirectToRoute('admin_user_index');
    }

    public function export(AdminContext $context, UserRepository $userRepository): Response
    {

        $users = $userRepository->findAll();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'pseudo' => $user->getPseudo(),
                'createdDate' => $user->getCreatedDate()?->format('Y-m-d'),
                'roles' => $user->getRoles(),
                'isBanned' => $user->isBanned(),
            ];
        }

        $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return new Response($jsonContent, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="users_export.json"',
        ]);
    }
}
