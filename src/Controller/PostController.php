<?php 

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Dictionary\PostStatus;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/blog/post/list', name: 'blog_post_list')]
    public function list(PostRepository $postRepository)
    {
        $posts = $postRepository->findBy([
            'status' => PostStatus::STATUS_PUBLISHED
        ]);

        return $this->render("customer/post/list.html.twig",[
            'posts' => $posts
        ]);
    }

    #[Route('/blog/post/show/{id}', name: 'blog_post_show')]
    public function show(int $id,PostRepository $postRepository,CommentRepository $commentRepository,Request $request,EntityManagerInterface $em)
    {
        $post = $postRepository->find($id);

        if(!$post)
        {
            $this->addFlash("danger","Article introuvable");
            return $this->redirectToRoute("blog_post_list");
        }

        $comments = $commentRepository->findBy([
            'post' => $post
        ],
        [
           'createdAt' => 'DESC' 
        ]);


        $comment = new Comment();

        $form = $this->createForm(CommentType::class,$comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setPost($post);
            $comment->setUser($this->getUser());

            $em->persist($comment);
            $em->flush();

            $this->addFlash("success","Ton commentaire a bien été publié");
            return $this->redirectToRoute("blog_post_show",['id' => $id]);
        }

        return $this->render("customer/post/show.html.twig",[
            'post' => $post,
            'form' => $form->createView(),
            'comments' => $comments
        ]);
    }
}