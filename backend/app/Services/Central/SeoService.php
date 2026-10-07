<?php

declare(strict_types=1);

namespace App\Services\Central;

class SeoService
{
    private string $title = '';

    private string $description = '';

    private string $canonical = '';

    private string $ogImage = '';

    private string $ogType = 'website';

    private array $schema = [];

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function setCanonical(string $canonical): static
    {
        $this->canonical = $canonical;

        return $this;
    }

    public function setOgImage(string $ogImage): static
    {
        $this->ogImage = $ogImage;

        return $this;
    }

    public function setOgType(string $ogType): static
    {
        $this->ogType = $ogType;

        return $this;
    }

    public function setSchema(array $schema): static
    {
        $this->schema = $schema;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCanonical(): string
    {
        return $this->canonical;
    }

    public function getOgImage(): string
    {
        return $this->ogImage;
    }

    public function getOgType(): string
    {
        return $this->ogType;
    }

    public function getSchema(): array
    {
        return $this->schema;
    }
}
