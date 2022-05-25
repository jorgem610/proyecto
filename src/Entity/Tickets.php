<?php

namespace App\Entity;

use App\Repository\TicketsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass=TicketsRepository::class)
 */
class Tickets
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LineaTicket", mappedBy="ticket", orphanRemoval=true, cascade={"persist"})
     *
     */
    private $lineaTicket;

    

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuarios", inversedBy="ticket", cascade={"persist"})
     *  
     */

    private $usuario;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pagado;

 

     /**
     * @return Collection<int, LineaTicket>
     */
    public function getLineaTicket(): Collection
    {
        return $this->lineaTicket;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }
    
    public function getUsuario(){
        return $this->usuario;
    }

    public function setUsuario($usuario){
        
        $this->usuario = $usuario;

        return $this;
    }

    public function getPagado(): ?bool
    {
        return $this->pagado;
    }

    public function setPagado(bool $pagado): self
    {
        $this->pagado = $pagado;

        return $this;
    }

   

    public function setLineaTicket(?LineaTicket $lineaTicket): self
    {
        $this->lineaTicket = $lineaTicket;

        return $this;
    }
}
