<?php
namespace App\Controller;
use App\Form\DebtType;
use App\Entity\Debt;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class NewDebtController extends Controller
{
    /**
     * @Route("/newdebt", name="newdebt")
     */
    public function addAction(Request $request, Swift_Mailer $mailer)
    {
        // 1) build the form
        $debt = new Debt();
        $form = $this->createForm(DebtType::class, $debt);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $submittedDebt = $form->getData();
            // 3) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($debt);
            $entityManager->flush();
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $request->getSession()->getFlashBag()->add('notice', 'Debt saved');
            $giver = $submittedDebt->getGiver();
            $receiver = $submittedDebt->getReceiver();
            $messageGiver = (new \Swift_Message('Confirmation de réception de dette'))
                ->setFrom(['lafrite.labarquette@gmail.com' => 'La frite'])
                ->setTo($giver->getEmail())
                ->setBody('Vous avez reçu une dette');
            $messageReceiver = (new \Swift_Message('Confirmation de création de dette'))
                ->setFrom(['lafrite.labarquette@gmail.com' => 'La frite'])
                ->setTo($receiver->getEmail())
                ->setBody('Votre dette a bien été créée');
            $mailer->send($messageGiver);
            $mailer->send($messageReceiver);
            return $this->redirectToRoute('profil');
        }
        return $this->render(
            'Debt/index.html.twig',
            array('form' => $form->createView())
        );
    }
}