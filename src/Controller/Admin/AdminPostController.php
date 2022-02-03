<?php 

namespace App\Controller\Admin;

use App\Dictionary\PostStatus;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPostController extends AbstractController
{
    #[Route('/admin/post/list', name: 'admin_post_list')]
    public function list(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();

        return $this->render("admin/post/list.html.twig",[
            'posts' => $posts
        ]);
    }

    #[Route('/admin/post/new', name: 'admin_post_new')]
    public function create(EntityManagerInterface $em, Request $request)
    {
        $post = new Post();

        $form  = $this->createForm(PostType::class,$post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($post);
            $em->flush();

            $this->addFlash("success","Le post a bien été créé.");
            return $this->redirectToRoute("admin_post_list");
        }

        return $this->render("admin/post/new.html.twig",[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/post/show/{id}', name: 'admin_post_show')]
    public function show(int $id, PostRepository $postRepository)
    {
        $post = $postRepository->find($id);

        if(!$post)
        {
            $this->addFlash("danger","Le post est introuvable.");
            return $this->redirectToRoute("admin_post_list");
        }

        return $this->render("admin/post/show.html.twig",[
            'post' => $post
        ]);
    }

    #[Route('/admin/post/togglestatus/{id}', name: 'admin_post_toggle_status')]
    public function toggleRole(int $id,PostRepository $postRepository,EntityManagerInterface $em)
    {
        $post = $postRepository->find($id);

        if(!$post)
        {
            $this->addFlash("danger","Post introuvable.");
            return $this->redirectToRoute("admin_post_list");
        }

        $status = $post->getStatus();

        if($status === PostStatus::STATUS_BROUILLON)
        {
            $post->setStatus(PostStatus::STATUS_PUBLISHED);
        }

        else
        {
            $post->setStatus(PostStatus::STATUS_BROUILLON);
        }

        $em->flush();

        $this->addFlash("success","Le statut du post a bien été modifié.");

        return $this->redirectToRoute("admin_post_show",['id' => $id]);
    }
}