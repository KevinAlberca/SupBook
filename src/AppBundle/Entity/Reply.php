<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reply
 *
 * @ORM\Table(name="reply")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReplyRepository")
 */
class Reply
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_thread", type="integer")
     */
    private $idThread;

    /**
     * @var int
     *
     * @ORM\Column(name="id_author", type="integer")
     */
    private $idAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="post_date", type="datetime")
     */
    private $postDate;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idThread
     *
     * @param integer $idThread
     *
     * @return Reply
     */
    public function setIdThread($idThread)
    {
        $this->idThread = $idThread;

        return $this;
    }

    /**
     * Get idThread
     *
     * @return int
     */
    public function getIdThread()
    {
        return $this->idThread;
    }

    /**
     * Set idAuthor
     *
     * @param integer $idAuthor
     *
     * @return Reply
     */
    public function setIdAuthor($idAuthor)
    {
        $this->idAuthor = $idAuthor;

        return $this;
    }

    /**
     * Get idAuthor
     *
     * @return int
     */
    public function getIdAuthor()
    {
        return $this->idAuthor;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Reply
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set postDate
     *
     * @param \DateTime $postDate
     *
     * @return Reply
     */
    public function setPostDate($postDate)
    {
        $this->postDate = $postDate;

        return $this;
    }

    /**
     * Get postDate
     *
     * @return \DateTime
     */
    public function getPostDate()
    {
        return $this->postDate;
    }
}

