<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Bukashk0zzz\FilterBundle\Annotation\FilterAnnotation as Filter;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Interspace
 *
 * @ORM\Table(name="interspace")
 * @ORM\Entity(repositoryClass="App\Repository\InterspaceRepository")
 *
 * @UniqueEntity("id")
 */
class Interspace
{
    /**
     * @var int
     *
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
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     *
     * @Assert\NotBlank(message="Это значение не должно быть пустым.")
     * @Assert\Type("numeric", message="Это значение должно быть числом.")
     * @Assert\NotNull()
     * @Assert\Regex(pattern="/^[0-9]{1,8}(\.[0-9][0-9]?)?$/", match=true, message="Вы ввели некорректное значение.")
     * @Filter("StripTags")
     */
    private $price;

    /**
     * @var Date
     *
     * @ORM\Column(name="start_date", type="date")
     *
     * @Assert\NotBlank(message="Это значение не должно быть пустым.")
     * @Assert\Date(message="Это значение должно быть датой.")
     * @Assert\NotNull()
     * @Filter("StripTags")
     */
    private $startDate;

    /**
     * @var Date
     *
     * @ORM\Column(name="finish_date", type="date")
     *
     * @Assert\NotBlank(message="Это значение не должно быть пустым.")
     * @Assert\Date(message="Это значение должно быть датой.")
     * @Assert\NotNull()
     * @Filter("StripTags")
     */
    private $finishDate;

    /**
     * @var Product|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="interspaces")
     */
    public $product;

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
     * Set price
     *
     * @param string $price
     *
     * @return Interspace
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get startDate
     *
     * @return Date
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set startDate
     *
     * @param Date $startDate
     *
     * @return Interspace
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get finishDate
     *
     * @return Date
     */
    public function getFinishDate()
    {
        return $this->finishDate;
    }

    /**
     * Set finishDate
     *
     * @param Date $finishDate
     *
     * @return Interspace
     */
    public function setFinishDate($finishDate)
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function addProduct(Product $product)
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
        }
    }
}