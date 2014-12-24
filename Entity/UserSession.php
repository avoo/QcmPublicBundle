<?php

namespace Qcm\Bundle\PublicBundle\Entity;

use Qcm\Bundle\CoreBundle\Entity\UserSession as BaseUserSession;

/**
 * Class UserSession
 */
class UserSession extends BaseUserSession
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
