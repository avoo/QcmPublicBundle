<?php
namespace Qcm\Bundle\PublicBundle\Listener;

use Qcm\Bundle\CoreBundle\Question\QuestionInteract;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class QuestionnaireListener
 */
class QuestionnaireListener
{
    const END_REDIRECT = 'qcm_public_question_end';

    /**
     * @var QuestionInteract $questionInteract
     */
    protected $questionInteract;

    /**
     * @var RouterInterface $router
     */
    protected $router;

    /**
     * @var SecurityContextInterface $securityContext
     */
    private $securityContext;

    /**
     * @var KernelInterface $kernel
     */
    private $kernel;

    /**
     * Construct
     *
     * @param QuestionInteract         $questionInteract
     * @param RouterInterface          $router
     * @param SecurityContextInterface $securityContext
     * @param KernelInterface          $kernel
     */
    public function __construct(QuestionInteract $questionInteract,
        RouterInterface $router,
        SecurityContextInterface $securityContext,
        KernelInterface $kernel)
    {
        $this->questionInteract = $questionInteract;
        $this->router = $router;
        $this->securityContext = $securityContext;
        $this->kernel = $kernel;
    }

    /**
     * Check questionnaire timeout
     *
     * @param GetResponseEvent $event
     *
     * @return null|RedirectResponse
     */
    public function questionnaireCheck(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType() ||
            ($event->getRequest()->request && 'html' !== $event->getRequest()->getRequestFormat())
        ) {
            return;
        }

        if (!is_null($this->securityContext->getToken()) &&
            $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY') &&
            !$this->questionInteract->isStarted()
        ) {
            $this->questionInteract->getQuestionnaireStarted($this->securityContext->getToken()->getUser());

            $url = $this->router->generate('qcm_public_question_reply');
            $response = new RedirectResponse($url);
            $event->setResponse($response);

            return $response;
        }

        if ($this->questionInteract->isStarted()) {
            $configuration = $this->questionInteract->getUserConfiguration();

            /** @var \DateTime $startDate */
            $startDate = $configuration['startAt'];
            $now = new \DateTime();
            $startDate->add(new \DateInterval('PT' . $configuration['timeout'] . 'S'));

            if ($startDate < $now) {
                $this->questionInteract->endQuestionnaire($now);

                $url = $this->router->generate(self::END_REDIRECT);
                $response = new RedirectResponse($url);
                $event->setResponse($response);

                return $response;
            }
        }
    }
}
