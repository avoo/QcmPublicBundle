<?php

namespace Qcm\Bundle\PublicBundle\Entity;

use Qcm\Bundle\CoreBundle\Entity\Answer as BaseAnswer;

/**
 * Class Answer
 */
class Answer extends BaseAnswer
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
