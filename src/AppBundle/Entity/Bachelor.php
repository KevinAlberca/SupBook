<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bachelor
 *
 * @ORM\Table(name="bachelor")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BachelorRepository")
 */
class Bachelor
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    public $name;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    public $title;

    /**
     * @var string
     *
     * @ORM\Column(name="shortcut", type="string", length=10)
     */
    public $shortcut;


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
     * Set name
     *
     * @param string $name
     *
     * @return Bachelor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Bachelor
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getShortcut()
    {
        return $this->shortcut;
    }

    /**
     * @param string $shortcut
     */
    public function setShortcut($shortcut)
    {
        $this->shortcut = $shortcut;
    }
}

