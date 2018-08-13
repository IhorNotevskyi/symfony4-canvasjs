<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Bukashk0zzz\FilterBundle\Annotation\FilterAnnotation as Filter;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 *
 * @UniqueEntity("id")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Assert\Type("integer", message="Это значение должно быть целым числом.")
     * @Assert\Length(max = 11, maxMessage="Максимальная длина 11 символов.")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank(message="Это значение не должно быть пустым.")
     * @Assert\Type("string", message="Это значение должно быть строкой.")
     * @Assert\Length(max = 255, maxMessage="Максимальная длина 255 символов.")
     * @Filter("StripTags")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     *
     * @Assert\NotBlank(message="Это значение не должно быть пустым.")
     * @Assert\Type("string", message="Это значение должно быть строкой.")
     * @Assert\Length(max = 5000, maxMessage="Максимальная длина 5000 символов.")
     * @Filter("StripTags")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="base_price", type="decimal", precision=10, scale=2)
     *
     * @Assert\NotBlank(message="Это значение не должно быть пустым.")
     * @Assert\Type("numeric", message="Это значение должно быть числом.")
     * @Assert\Regex(pattern="/^[0-9]{1,8}(\.[0-9][0-9]?)?$/", match=true, message="Вы ввели некорректное значение.")
     * @Filter("StripTags")
     */
    private $basePrice;

    /**
     * @var Date
     *
     * @ORM\Column(name="created_at", type="date")
     *
     * @Assert\NotBlank(message="Это значение не должно быть пустым.")
     * @Assert\Date(message="Это значение должно быть датой.")
     * @Assert\NotNull()
     * @Filter("StripTags")
     */
    private $createdAt;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Interspace", mappedBy="product", cascade={"persist"})
     */
    public $interspaces;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->interspaces = new ArrayCollection();
    }

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
     * @return Product
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
     * Set content
     *
     * @param string $content
     *
     * @return Product
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
     * Set basePrice
     *
     * @param string $basePrice
     *
     * @return Product
     */
    public function setBasePrice($basePrice)
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    /**
     * Get basePrice
     *
     * @return string
     */
    public function getBasePrice()
    {
        return $this->basePrice;
    }

    /**
     * Get createdAt
     *
     * @return Date
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param Date $createdAt
     *
     * @return Product
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getInterspaces()
    {
        return $this->interspaces;
    }

    public function addIntespace(Interspace $interspace)
    {
        $interspace->setProduct($this);
        $this->interspaces->add($interspace);
    }

    public function removeInterspace(Interspace $interspace)
    {
        $this->interspaces->removeElement($interspace);
    }
}