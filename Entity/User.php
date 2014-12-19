<?php

namespace Qcm\Bundle\PublicBundle\Entity;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
