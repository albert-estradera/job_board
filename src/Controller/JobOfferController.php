<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Form\JobOfferType;
use App\Repository\JobOfferRepository;
use App\Entity\Applicant;
use App\Form\ApplicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Mime\Email;

/**
 * @Route("/job/offer")
 */
class JobOfferController extends AbstractController
{
    /**
     * @IsGranted("ROLE_COMPANY_OWNER") 
     * @Route("/", name="app_job_offer_index", methods={"GET"})
     */
    public function index(): Response
    {

        $user = $this->getUser();
        $company = $user->getCompany();

        if( !$company ){

            return $this->redirectToRoute("company_create");
        }

        return $this->render('job_offer/index.html.twig', [
            'job_offers' => $company->getJobOffers(),
        ]);
    }

    /**
     * @IsGranted("ROLE_COMPANY_OWNER") 
     * @Route("/new", name="app_job_offer_new", methods={"GET", "POST"})
     */
    public function new(Request $request, JobOfferRepository $jobOfferRepository): Response
    {
        $jobOffer = new JobOffer();

        $jobOffer->setCompany($this->getUser()->getCompany());

        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobOfferRepository->add($jobOffer, true);

            return $this->redirectToRoute('app_job_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('job_offer/new.html.twig', [
            'job_offer' => $jobOffer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_job_offer_show", methods={"GET"})
     */
    public function show(JobOffer $jobOffer): Response
    {
        return $this->render('job_offer/show.html.twig', [
            'job_offer' => $jobOffer,
        ]);
    }

    /**
     * @Security(" (is_granted('ROLE_COMPANY_OWNER') and jobOffer.getCompany() == user.getCompany()) or is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="app_job_offer_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, JobOffer $jobOffer, JobOfferRepository $jobOfferRepository): Response
    {
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobOfferRepository->add($jobOffer, true);

            return $this->redirectToRoute('app_job_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('job_offer/edit.html.twig', [
            'job_offer' => $jobOffer,
            'form' => $form,
        ]);
    }

    /**
     * @Security(" (is_granted('ROLE_COMPANY_OWNER') and jobOffer.getCompany() == user.getCompany()) or is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="app_job_offer_delete", methods={"POST"})
     */
    public function delete(Request $request, JobOffer $jobOffer, JobOfferRepository $jobOfferRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jobOffer->getId(), $request->request->get('_token'))) {
            $jobOfferRepository->remove($jobOffer, true);
        }

        return $this->redirectToRoute('app_job_offer_index', [], Response::HTTP_SEE_OTHER);
    }



    /**
    * @Route("/{id}/apply", name="offer_apply") 
    */
    public function apply(Int $id, EntityManagerInterface $entityManager, Request $request, MailerInterface $mailer){

        $offer = $entityManager->getRepository(JobOffer::class)->find($id);

        if(!$offer)
            throw new NotFoundHttpException();
        
        $applicant=new Applicant();

        $form=$this->createForm(ApplicationType::class, $applicant);

        $form->handleRequest($request);

        $mailer->send(

            (new Email())
            ->from("aaa@aa.es")
            ->to($offer->getCompany()->getOwner()->getEmail())
            ->subject("New application recieved!")
            ->html("string")
        );

        if( $form->isSubmitted() && $form->isValid() ){

            $entityManager->persist($applicant); //perpara la sql no la ejecuta. Es como un encolador de sentencias sql
            $entityManager->flush();
            
            $this->addFlash("success", "Your application has been received");

            return $this->redirectToRoute("offer_index");
        }

        return $this->render('offer/apply.html.twig',[

            "offer" => $offer,
            "form" => $form->createView()
        ]);
    }

}
