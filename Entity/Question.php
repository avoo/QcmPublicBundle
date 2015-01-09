<?php

namespace Qcm\Bundle\PublicBundle\Entity;

use Qcm\Bundle\CoreBundle\Entity\Question as BaseQuestion;

/**
 * Class Question
 */
class Question extends BaseQuestion
{
    /**
     * @var integer $id
     */
    public $id;

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
