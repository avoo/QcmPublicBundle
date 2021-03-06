<?php
namespace Qcm\Bundle\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('qcm_admin_user_list'));
        }

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
     * @return Response
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
        $form = $this->createForm($formType, $questionInteract->getUserConfiguration());

        return $this->render('QcmPublicBundle:Question:reply.html.twig', array(
            'question' => $question,
            'form' => $form->createView()
        ));
    }

    /**
     * Update action
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function nextQuestionAction(Request $request)
    {
        $questionInteract = $this->get('qcm_core.question.interact');
        $formType = $this->get('qcm_core.form.type.reply');
        $form = $this->createForm($formType);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $questionInteract->saveStep($form);
            $configuration = $questionInteract->getUserConfiguration();

            if (!is_null($configuration->getTimeout()) && $configuration->getQuestions()->count() - $configuration->getAnswers()->count() == 0 ||
                false === $questionInteract->getNextQuestion()
            ) {
                $url = 'qcm_public_question_summary';

                if (!is_null($configuration->getTimePerQuestion())) {
                    $url = 'qcm_public_question_end';
                }

                return $this->redirect($this->generateUrl($url));
            }
        }

        return $this->redirect($this->generateUrl('qcm_public_question_reply'));
    }

    /**
     * Get previous question
     *
     * @return RedirectResponse
     */
    public function prevQuexstionAction()
    {
        $questionInteract = $this->get('qcm_core.question.interact');
        $questionInteract->getPrevQuestion();

        return $this->redirect($this->generateUrl('qcm_public_question_reply'));
    }

    /**
     * Get specific question
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function specificQuestionAction(Request $request)
    {
        $questionInteract = $this->get('qcm_core.question.interact');

        if (false === $questionInteract->getSpecificQuestion($request->get('id'))) {
            throw new NotFoundHttpException('Question not found.');
        }

        return $this->redirect($this->generateUrl('qcm_public_question_reply'));
    }

    /**
     * @return Response
     */
    public function summaryAction()
    {
        $questionInteract = $this->get('qcm_core.question.interact');

        if (!$questionInteract->isStarted()) {
            return $this->redirect($this->generateUrl('qcm_public_homepage'));
        }

        $configuration = $questionInteract->getUserConfiguration();
        $answers = $configuration->getAnswers();

        return $this->render('QcmPublicBundle:Question:summary.html.twig', array(
            'answers' => $answers
        ));
    }

    /**
     * @return Response
     */
    public function endQuestionnaireAction()
    {
        $questionInteract = $this->get('qcm_core.question.interact');

        if ($questionInteract->isStarted()) {
            $questionInteract->endQuestionnaire(new \DateTime());
        }

        return $this->render('QcmPublicBundle:Question:end.html.twig');
    }
}
