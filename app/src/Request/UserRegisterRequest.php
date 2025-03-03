<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterRequest
{
    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Email(message: 'Invalid email address')]
    private string $email;

    #[Assert\NotBlank(message: 'Password is required')]
    #[Assert\Length(min: 8, minMessage: 'Password must be at least 6 characters long')]
    private string $password;

    #[Assert\NotBlank(message: 'Name is required')]
    #[Assert\Length(min: 3, max: 50, minMessage: 'Name must be at least 3 characters long', maxMessage: 'Name cannot be longer than 50 characters')]
    private string $name;

    #[Assert\NotBlank(message: 'Phone is required')]
    #[Assert\Regex(pattern: '/^\+[0-9]{11}$/', message: 'Phone number must be char "+" and 11 digits')]
    private string $phone;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
