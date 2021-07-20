<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{

    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            TextField::new('username'),
            ChoiceField::new('roles')->setChoices(['ROLE_ADMIN' => 'ROLE_ADMIN', 'ROLE_USER' => 'ROLE_USER'])->allowMultipleChoices(),
            BooleanField::new('is_verified'),
            DateField::new('last_login')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $url = $this->adminUrlGenerator->setRoute('email_form')->generateUrl();
        // if the method is not defined in a CRUD controller, link to its route
        $emailForm = Action::new('email_form', 'Email Form', 'fa fa-envelope')
        // if the route needs parameters, you can define them:
            // 1) using an array
            ->linkToRoute('email_form', function (User $user): array {
                return
                    [
                        'user' => $user,
                        'id' => $user->getId(),
                        'email' => $user->getEmail(),
                        'last_login' => $user->getLastLogin()
                    ];
            });

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_DETAIL, $emailForm);
    }
}
