<?


namespace App\Controller;

use App\Entity\JobOffer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CustomMessageService;

class _firstController extends AbstractController{

	/**
	* @Route("/") 
	*/
	public function index(EntityManagerInterface $entityManager, CustomMessageService $customMessage): Response {

		$offers=$entityManager->getRepository(JobOffer::class)->findAll();

		$customMessage = $customMessage->getMessage();

		return $this->render('offer/index.html.twig',[
			"offers" => $offers,
			"customMessage" => $customMessage
		]);
	}
}


?>