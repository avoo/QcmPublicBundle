<?php
namespace Qcm\Bundle\PublicBundle\Controller;

use Qcm\Bundle\CoreBundle\Doctrine\ORM\CategoryRepository;
use Qcm\Component\Category\Model\CategoryInterface;
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
        $questionInteract = $this->get('qcm_core.question.interact');

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('qcm_admin_user_list'));
        }

        if ($questionInteract->isStarted()) {
            return $this->redirect($this->generateUrl('qcm_public_question_reply'));
        }

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $questionnaires = $manager->getRepository('QcmPublicBundle:UserSession')->getQuestionnairesByUser($user);
        }

        /** @var UserSessionInterface $questionnaire */
        foreach ($questionnaires as $key => &$questionnaire) {
            $configuration = $questionnaire->getConfiguration();
            if (!is_null($configuration->getEndAt())) {
                unset($questionnaires[$key]);
                continue;
            }

            $categories[$questionnaire->getId()] = array_map(function($category) {
                /** @var CategoryInterface $category */

                return $category->getName();
            }, $questionnaire->getConfiguration()->getCategories()->toArray());
        }

        return $this->render('QcmPublicBundle:Home:index.html.twig', array(
            'categories' => $categories,
            'questionnaires' => $questionnaires
        ));
    }
}
