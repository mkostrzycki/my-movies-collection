<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovieCastRole
 *
 * @ORM\Table(name="movie_cast_role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieCastRoleRepository")
 */
class MovieCastRole
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="movieCastRoles")
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cast", inversedBy="movieCastRoles")
     */
    private $cast;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Role", inversedBy="movieCastRoles")
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="role_description", type="string", length=255, nullable=true)
     */
    private $roleDescription;


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
     * Set movie
     *
     * @param Movie $movie
     * @return MovieCastRole
     */
    public function setMovie(Movie $movie = null)
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * Get movie
     *
     * @return Movie
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * Set cast
     *
     * @param Cast $cast
     * @return MovieCastRole
     */
    public function setCast(Cast $cast = null)
    {
        $this->cast = $cast;

        return $this;
    }

    /**
     * Get cast
     *
     * @return Cast
     */
    public function getCast()
    {
        return $this->cast;
    }

    /**
     * Set role
     *
     * @param Role $role
     * @return MovieCastRole
     */
    public function setRole(Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set roleDescription
     *
     * @param string $roleDescription
     * @return MovieCastRole
     */
    public function setRoleDescription($roleDescription)
    {
        $this->roleDescription = $roleDescription;

        return $this;
    }

    /**
     * Get roleDescription
     *
     * @return string 
     */
    public function getRoleDescription()
    {
        return $this->roleDescription;
    }
}
