<?php

namespace Qcm\Bundle\PublicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Qcm\Bundle\CoreBundle\Entity\User as BaseUser;

/**
 * Class User
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var ArrayCollection $questionnaires
     */
    protected $questionnaires;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Questionnaires list
     *
     * @return ArrayCollection
     */
    public function getQuestionnaires()
    {
        return $this->questionnaires;
    }
}
