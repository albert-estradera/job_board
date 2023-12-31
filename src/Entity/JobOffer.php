<?php

namespace App\Entity;

use App\Repository\JobOfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobOfferRepository::class)
 */
class JobOffer
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
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="jobOffers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\ManyToMany(targetEntity=Applicant::class, inversedBy="jobOffers")
     */
    private $applicants;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;


    public function __construct()
    {
        $this->applicants = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCompany(): ?company
    {
        return $this->company;
    }

    public function setCompany(?company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection<int, applicant>
     */
    public function getApplicants(): Collection
    {
        return $this->applicants;
    }

    public function addApplicant(applicant $applicant): self
    {
        if (!$this->applicants->contains($applicant)) {
            $this->applicants[] = $applicant;
        }

        return $this;
    }

    public function removeApplicant(applicant $applicant): self
    {
        $this->applicants->removeElement($applicant);

        return $this;
    }
}
