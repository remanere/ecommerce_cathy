<?php 

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    #[Route('/admin/user/list', name: 'admin_user_list')]
    public function listUser(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render("admin/user/list.html.twig",[
            'users' => $users
        ]);
    }
    #[Route('/admin/user/togglerole/{id}', name: 'admin_user_toggle_role')]
    public function toggleRole(int $id,UserRepository $userRepository,EntityManagerInterface $em)
    {
  
        $user = $userRepository->find($id);

        if(!$user)
        {
            $this->addFlash("danger","L'utilisateur introuvable.");
            return $this->redirectToRoute("admin_user_list");
        }

        $role = $user->getRoles()[0];

        if($role === "ROLE_ADMIN")
        {
            $user->setRoles([]);
        }
        else
        {
            $user->setRoles(["ROLE_ADMIN"]);
        }

        $em->flush();
        $this->addFlash("success","Le rôle de l'utilisateur : " . $user->getEmail() . " a bien été modifié.");
        return $this->redirectToRoute("admin_user_list");
    }
}