<?php

namespace App\Entity;

use App\Repository\ProductosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ProductosRepository::class)
 * @ORM\Table(name="productos", indexes={@ORM\Index(columns={"nombre", "contenido"}, flags={"fulltext"})})
 */
class Productos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", length=65535)
     */
    private $contenido;

    /**
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $fecha_entrada;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock_minimo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categoria", inversedBy="productos")
    */

    private $categoria;

       /**
     * @ORM\OneToMany(targetEntity="App\Entity\LineaTicket", mappedBy="productos")
     */
    private $lineaTicket;

    /**
     * @ORM\OneToMany(targetEntity=Imagenes::class, mappedBy="producto", orphanRemoval=true, cascade={"persist"})
     */
    private $imagenes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $especial;

    //Le asignamos por defecto el activo a false y creamos un array para las imagenes
    public function __construct()
    {
        $this->imagenes = new ArrayCollection();
        $this->activo = false;
    }

     

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): ?self
    {
        $this->id = $id;

        return $this;
    }

    
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getcontenido(): ?string
    {
        return $this->contenido;
    }

    public function setcontenido(string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getFechaEntrada(): ?\DateTimeInterface
    {
        return $this->fecha_entrada;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getLineaTicket(): ?LineaTicket
    {
        return $this->lineaTicket;
    }

    public function setLineaTicket(?LineaTicket $lineaTicket): self
    {
        $this->lineaTicket = $lineaTicket;

        return $this;
    }

    

    // public function setFechaEntrada(\DateTimeInterface $fecha_entrada): self
    // {
    //     $this->fecha_entrada = $fecha_entrada;

    //     return $this;
    // }

    public function getStockMinimo(): ?int
    {
        return $this->stock_minimo;
    }

    public function setStockMinimo(int $stock_minimo): self
    {
        $this->stock_minimo = $stock_minimo;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * @return Collection<int, Imagenes>
     */
    public function getImagenes(): Collection
    {
        return $this->imagenes;
    }

    public function addImagen(Imagenes $imagene): self
    {
        if (!$this->imagenes->contains($imagene)) {
            $this->imagenes[] = $imagene;
            $imagene->setProducto($this);
        }

        return $this;
    }

    public function removeImagene(Imagenes $imagene): self
    {
        if ($this->imagenes->removeElement($imagene)) {
            // set the owning side to null (unless already changed)
            if ($imagene->getProducto() === $this) {
                $imagene->setProducto(null);
            }
        }

        return $this;
    }

    public function getEspecial(): ?int
    {
        return $this->especial;
    }

    public function setEspecial(?int $especial): self
    {
        $this->especial = $especial;

        return $this;
    }
}
