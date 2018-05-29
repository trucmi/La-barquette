<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{


    /**
     * @Route("/blog", name="blog")
     */
    public function blog(PostRepository $PostRepository)
    {
        $getPost = $PostRepository->getBlog();

        return $this->render('blog/blog.html.twig', array("post" => $getPost));
    }

   /**
     * @Route("/blog/{id}", name="blogId")
     */
    public function blogId(Post $post)
    {

        return $this->render(
            'blog/blog.html.twig', array("post" => $post));
    }
}



