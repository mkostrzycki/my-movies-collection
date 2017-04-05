<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Movie
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieRepository")
 */
class Movie
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="yearOfProduction", type="smallint", nullable=true)
     */
    private $yearOfProduction;


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
     * Set title
     *
     * @param string $title
     * @return Movie
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
     * Set yearOfProduction
     *
     * @param integer $yearOfProduction
     * @return Movie
     */
    public function setYearOfProduction($yearOfProduction)
    {
        $this->yearOfProduction = $yearOfProduction;

        return $this;
    }

    /**
     * Get yearOfProduction
     *
     * @return integer 
     */
    public function getYearOfProduction()
    {
        return $this->yearOfProduction;
    }
}
