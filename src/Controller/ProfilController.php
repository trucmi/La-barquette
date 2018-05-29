<?php
namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;
use App\Entity\Debt;
class ProfilController extends Controller
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index()
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    /**
     * @Route("/archived/{id}", name="archived")
     */
    public function archived($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var Debt $debt */
        $debt =  $entityManager->getRepository(Debt::class)->find($id);


        //$debt =  $entityManager->getRepository(Debt::class)->findBy('isArchived' => true);
        $debt->setIsArchived(true);

        $entityManager->flush();

        return $this->redirectToRoute('profil');
    }


}