<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ApplicationRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\ApplicationBySlugController;
use App\Controller\ApplicationCoverController;
use ApiPlatform\Core\Annotation\ApiProperty;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "application")]
#[
    ApiResource(
        attributes: ["route_prefix" => "/admin"],
        collectionOperations: [
            "get",
            "post" => [
                "controller" => ApplicationCoverController::class,
                "deserialize" => false,
                "openapi_context" => [
                    "requestBody" => [
                        "description" => "File upload to an existing resource (job application)",
                        "required" => true,
                        "content" => [
                            "multipart/form-data" => [
                                "schema" => [
                                    "type" => "object",
                                    "properties" => [
                                        "company" => [
                                            "description" => "The company name",
                                            "type" => "string",
                                            "example" => "Pagac and Sons",
                                            "required" => true,
                                        ],
                                        "position" => [
                                            "description" => "The position",
                                            "type" => "string",
                                            "example" => "Infantry",
                                        ],
                                        "location" => [
                                            "description" => "The location of the job (settlement)",
                                            "type" => "string",
                                            "example" => "Port Martinechester",
                                        ],
                                        "link" => [
                                            "description" => "The URL for the job offer",
                                            "type" => "string",
                                            "example" => "http://donnelly.com/molestiae-quam-laboriosam-velit-molestiae-maxime",
                                        ],
                                        "email" => [
                                            "description" => "The company's email address",
                                            "type" => "string",
                                            "example" => "zieme.tressie@example.com",
                                        ],
                                        "phoneNumber" => [
                                            "description" => "The company's phone number (optional)",
                                            "type" => "string",
                                            "example" => "+19172615809",
                                        ],
                                        "subject" => [
                                            "description" => "The subject of the cover letter",
                                            "type" => "string",
                                            "example" => "Autem non amet beatae ut.",
                                        ],
                                        "message" => [
                                            "description" => "The message body of the cover letter",
                                            "type" => "string",
                                            "example" => "Est aut incidunt aut nam nam magnam. Id voluptatum numquam sint. Neque non dolores facilis deserunt. Facilis aut possimus non aut autem commodi molestiae. Doloremque non inventore porro quod. Est possimus ratione ab ea.",
                                        ],
                                        "notes" => [
                                            "description" => "Personal notes (optional)",
                                            "type" => "string",
                                            "example" => "esse aut doloremque quo doloremque deleniti qui eius",
                                        ],
                                        "applicationDate" => [
                                            "description" => "The date the job application has been created",
                                            "type" => "string",
                                            "example" => "2022-09-13T02:00:45+02:00",
                                        ],
                                        "slug" => [
                                            "description" => "The slug (optional)",
                                            "type" => "string",
                                            "example" => "pagac",
                                        ],

                                        "isApplicationSent" => [
                                            "description" => "Has the application been sent?",
                                            "type" => "boolean",
                                            "example" => "false"
                                        ],
                                        "file" => [
                                            "type" => "string",
                                            "format" => "binary",
                                            "description" => "Upload a cover image of the application (optional)",
                                        ]
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        itemOperations: [
            "get",
            "put",
            "delete",
            # API platform is garbage -> this endpoint works in insomnia, curl, etc., but not on api platform page
            "get_by_slug" => [
                "method" => "GET",
                "path" => "/application/{slug}",
                "controller" => ApplicationBySlugController::class,
                "read" => false,
                'identifiers' => [],
                "openapi_context" => [
                    "parameters" => [
                        [
                            "name" => "slug",
                            "in" => "path",
                            "description" => "The slug of the company you applied for a job",
                            "type" => "string",
                            "required" => true,
                            "example" => "pagac",
                        ],
                    ],
                ],
            ],

        ],
        normalizationContext: ["groups" => ["read"]],
        denormalizationContext: ["groups" => ["write"]]
    ),
]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["read", "write"])]
    private ?string $company = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["read", "write"])]
    private ?string $position = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["read", "write"])]
    private ?string $location = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["read", "write"])]
    private ?string $link = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["read", "write"])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["read", "write"])]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["read", "write"])]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Groups(["read", "write"])]
    private ?string $message = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["read", "write"])]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["read", "write"])]
    private ?\DateTimeInterface $applicationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["read", "write"])]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["read", "write"])]
    private ?bool $isApplicationSent = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["read", "write"])]
    #[ApiProperty(
        iri: "http://schema.org/image",
        attributes: [
            "openapi_context" => [
                "type" => "string",
            ]
        ]
    )]
    private ?string $cover = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getApplicationDate(): ?\DateTimeInterface
    {
        return $this->applicationDate;
    }

    public function setApplicationDate(\DateTimeInterface $applicationDate = null): self
    {
        $applicationDate === null
            ?
            $this->applicationDate = new \DateTime('now')
            :
            $this->applicationDate = $applicationDate;

        return $this;
    }

    #[ORM\PrePersist]
    public function updatedTimestamps()
    {
        if ($this->applicationDate == null) {
            $this->applicationDate = new \DateTime('now');
        }
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function isIsApplicationSent(): ?bool
    {
        return $this->isApplicationSent;
    }

    public function setIsApplicationSent(?bool $isApplicationSent): self
    {
        $this->isApplicationSent = $isApplicationSent;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }
}
