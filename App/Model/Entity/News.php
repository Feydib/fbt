<?php

namespace App\Model\Entity;

/**
 * FbtNews
 *
 * @Table(name="fbt_news")
 * @Entity
 */
class News
{
    /**
     * @var integer
     *
     * @Column(name="idNews", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idnews;

    /**
     * @var \DateTime
     *
     * @Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    public function getIdnews() {
        return $this->idnews;
    }

    public function getDate() {
        return $this->date;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setIdnews($idnews) {
        $this->idnews = $idnews;
    }

    public function setDate(\DateTime $date) {
        $this->date = $date;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }


}
