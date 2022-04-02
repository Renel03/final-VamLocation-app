<?php

namespace App\Service;
use Pagerfanta\Pagerfanta;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Serializer\Annotation\Groups;

class DemandsCollection
{
    /**
     * @Type("array<App\Entity\Demand>")
     * @Groups({"list", "hide"})
     */
    public $data;

    /**
     * @Groups({"list", "hide"})
     */
    public $meta;
    
    public function __construct(Pagerfanta $pagerfanta, $query = [])
    {
        $this->data = $pagerfanta->getCurrentPageResults();
        $this->addMeta('current_page', $pagerfanta->getCurrentPage());
        $this->addMeta('has_previous_page', $pagerfanta->hasPreviousPage());
        $this->addMeta('has_next_page', $pagerfanta->hasNextPage());
        $this->addMeta('per_page', $pagerfanta->getMaxPerPage());
        $this->addMeta('total_items', $pagerfanta->getNbResults());
        $this->addMeta('total_pages', $pagerfanta->getNbPages());
    }
    
    public function addMeta($name, $value)
    {
        if (isset($this->meta[$name])) {
            throw new \LogicException(sprintf('This meta already exists. You are trying to override this meta, use the setMeta method instead for the %s meta.', $name));
        }
        
        $this->setMeta($name, $value);
    }
    
    public function setMeta($name, $value)
    {
        $this->meta[$name] = $value;
    }
}