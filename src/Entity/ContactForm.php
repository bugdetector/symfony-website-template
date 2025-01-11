<?php

namespace App\Entity;

use App\Repository\ContactFormRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

#[ORM\Entity(repositoryClass: ContactFormRepository::class)]
class ContactForm
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Email(message: "You must enter a valid email address")]
    #[NotNull(message: 'Please enter a valid email address.')]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Length(
        min: 4,
        minMessage: 'Please enter at least 4 characters',
        max: 15,
        maxMessage: "Please Enter maximum 15 characters"
    )]
    #[NotNull(message: 'Please enter a valid phone.')]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    #[Length(
        min: 4,
        minMessage: 'Please enter at least 4 characters'
    )]
    #[NotNull(message: 'Please enter your name.')]
    private ?string $name = null;

    #[ORM\Column(length: 1000)]
    #[Length(
        min: 15,
        minMessage: 'Please enter at least 15 characters'
    )]
    #[NotNull(message: 'Please enter your message.')]
    private ?string $message = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
