<?php

namespace App\Entity;

use App\Repository\LineaTicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LineaTicketRepository::class)
 */
class LineaTicket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="integer")
     */
    private $precio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Productos", inversedBy="lineaTicket")
     *  @ORM\JoinColumn(name="producto_id", referencedColumnName="id", onDelete="CASCADE")
    */

    private $productos;

     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tickets", inversedBy="lineaTicket")
     *  @ORM\JoinColumn(name="ticket_id", referencedColumnName="id", onDelete="CASCADE")
    */

    private $ticket;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function setPrecio(int $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getTicket(): ?Tickets
    {
        return $this->ticket;
    }

    public function setTicket(?Tickets $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function __construct()
    {
        $this->productos = new ArrayCollection();
    }

     /**
     * @return Collection<int, Productos>
     */
    public function getProductos(): Collection
    {
        return $this->productos;
    }

    public function setProductos(?Productos $productos): self
    {
        $this->productos = $productos;

        return $this;
    }
}
