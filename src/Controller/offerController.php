<?


namespace App\Controller;

use App\Entity\JobOffer;
use App\Entity\Applicant;
use App\Form\ApplicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class offerController extends AbstractController{

	/**
	* @Route("/", name="offer_index") 
	*/
	public function index(EntityManagerInterface $entityManager): Response {

		$offers=$entityManager->getRepository(JobOffer::class)->findAll();
		
		return $this->render('offer/index.html.twig',[
			"offers" => $offers,
		]);
	}


	/**
	* //@Route("job_offer/{id}/apply", name="offer_apply") 
	*/
	/*public function apply(Int $id, EntityManagerInterface $entityManager, Request $request){

		$offer = $entityManager->getRepository(JobOffer::class)->find($id);

		if(!$offer)
			throw new NotFoundHttpException();
		
		$applicant=new Applicant();

		$form=$this->createForm(ApplicationType::class, $applicant);

		$form->handleRequest($request);

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
	}*/

	/**
	* @IsGranted("ROLE_COMPANY_OWNER")
	* @Route("company/", name="company_offers_index")
	* @return Response
	*/
	/*public function companyOffers() : Response
	{
		$user = $this->getUser();
		$company = $user->getCompany();

		if( !$company ){

			return $this->redirectToRoute("company_create");
		}

		return $this->render( "offer/company_index_html.twig", 
		[
			'offers' => $company ? $company->getJobOffers() : [],

		]);
	}*/

}


?>