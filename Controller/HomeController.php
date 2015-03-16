<?php
namespace Qcm\Bundle\PublicBundle\Controller;

use Qcm\Component\User\Model\UserSessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 */
class HomeController extends Controller
{
    /**
     * Index action
     *
     * @return Response
     */
    public function indexAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $questionnaires = array();
        $categories = array();

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $questionnaires = $manager->getRepository('QcmPublicBundle:UserSession')->getQuestionnairesByUser($user);
        }

        $categoryRepository = $this->getDoctrine()->getRepository('QcmPublicBundle:Category');

        /** @var UserSessionInterface $questionnaire */
        foreach ($questionnaires as $questionnaire) {
            $categories[$questionnaire->getId()] = $categoryRepository->getCategoryByUserSession($questionnaire);
        }

        return $this->render('QcmPublicBundle:Home:index.html.twig', array(
            'categories' => $categories,
            'questionnaires' => $questionnaires
        ));
    }
}
