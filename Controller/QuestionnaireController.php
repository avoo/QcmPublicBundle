<?php
namespace Qcm\Bundle\PublicBundle\Controller;

use Qcm\Bundle\CoreBundle\Form\Type\ReplyFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class QuestionnaireController
 */
class QuestionnaireController extends Controller
{
    /**
     * Start action
     *
     * @return RedirectResponse
     */
    public function startAction()
    {
        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();
        $userSession = $manager->getRepository('QcmPublicBundle:UserSession')->findOneBy(array(
            'id' => $this->get('request_stack')->getCurrentRequest()->get('id'),
            'user' => $user,
        ));

        if (is_null($userSession)) {
            throw new NotFoundHttpException('Session not found.');
        }

        $questionInteract = $this->get('qcm_core.question.interact');
        $questionInteract->startQuestionnaire($userSession);

        return $this->redirect($this->generateUrl('qcm_public_question_reply'));
    }

    /**
     * Question reply action
     *
     * @return RedirectResponse
     */
    public function replyAction()
    {
        $questionInteract = $this->get('qcm_core.question.interact');

        if (!$questionInteract->isStarted()) {
            $this->get('session')->getFlashBag()->add('danger', 'qcm_public.questionnaire.not_started');

            return $this->redirect($this->generateUrl('qcm_public_homepage'));
        }

        $question = $questionInteract->getQuestion();
        $formType = $this->get('qcm_core.form.type.reply');
        var_dump($formType);die;
        $form = $this->createForm(new ReplyFormType($question), $questionInteract->getQuestionnaireConfiguration());

        return $this->render('QcmPublicBundle:Question:reply.html.twig', array(
            'question' => $question,
            'form' => $form->createView()
        ));
    }
}
