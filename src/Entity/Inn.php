<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Inn
{
    #[Assert\NotBlank]
    private string $value;

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
