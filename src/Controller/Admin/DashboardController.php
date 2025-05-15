<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Debate;
use App\Entity\Report;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(): Response
    {
        return $this->redirectToRoute('admin_report_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('DebateArena')
            ->setFaviconPath('favicon.ico');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud($this->translator->trans('admin.menu.report'), 'fa fa-flag', Report::class);
        yield MenuItem::linkToCrud('Creer Categorie', 'fa fa-tags', Category::class);
        yield MenuItem::linkToCrud($this->translator->trans('admin.menu.user'), 'fa fa-user', User::class);
        yield MenuItem::linkToUrl($this->translator->trans('admin.dashboard.home'), '', '/');

        yield MenuItem::section($this->translator->trans('admin.section.moderator'));
        yield MenuItem::linkToCrud($this->translator->trans('admin.menu.report'), 'fa fa-flag', Report::class);
        yield MenuItem::linkToCrud($this->translator->trans('admin.menu.debate'), 'fa fa-newspaper', Debate::class);
        yield MenuItem::linkToCrud('Creer Categorie', 'fa fa-tags', Category::class);

        yield MenuItem::section($this->translator->trans('admin.section.administrator'));
        yield MenuItem::linkToCrud($this->translator->trans('admin.menu.user'), 'fa fa-user', User::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->addMenuItems([
                MenuItem::linkToRoute($this->translator->trans('admin.adminUser.profile'), 'fa fa-id-card', 'app_user_show'),
            ]);
    }
}
