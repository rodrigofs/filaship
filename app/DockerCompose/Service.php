<?php

declare(strict_types = 1);

namespace Filaship\DockerCompose;

use Filaship\Contracts\DockerComposeComponentInterface;
use Filaship\DockerCompose\Service\BuildConfig;

final class Service implements DockerComposeComponentInterface
{
    /**
     * @param array<string> $ports
     * @param array<string> $volumes
     * @param array<string> $environment
     * @param array<string> $dependsOn
     * @param array<string> $networks
     * @param array<string> $labels
     * @param array<string>|string|null $command
     * @param array<string> $healthcheck
     * @param array<string> $extra
     */
    public function __construct(
        public string $name,
        public ?string $image = null,
        public ?BuildConfig $build = null,
        public array $ports = [],
        public array $volumes = [],
        public array $environment = [],
        public array $dependsOn = [],
        public array $networks = [],
        public array $labels = [],
        public array | string | null $command = null,
        public ?string $restart = null,
        public array $healthcheck = [],
        public array $extra = [],
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'image'       => $this->image,
            'build'       => $this->build?->serialize(),
            'ports'       => $this->ports,
            'volumes'     => $this->volumes,
            'environment' => $this->environment,
            'depends_on'  => $this->dependsOn,
            'networks'    => $this->networks,
            'labels'      => $this->labels,
            'command'     => $this->command,
            'restart'     => $this->restart,
            'healthcheck' => $this->healthcheck,
            'extra'       => $this->extra,
        ], fn ($value): bool => $value !== null && $value !== []);
    }

    public static function fromArray(string $name, array $data): self
    {
        return new self(
            name: $name,
            image: $data['image'] ?? null,
            build: BuildConfig::parse($data['build'] ?? null),
            ports: $data['ports'] ?? [],
            volumes: $data['volumes'] ?? [],
            environment: $data['environment'] ?? [],
            dependsOn: $data['depends_on'] ?? [],
            networks: $data['networks'] ?? [],
            labels: $data['labels'] ?? [],
            command: $data['command'] ?? null,
            restart: $data['restart'] ?? null,
            healthcheck: $data['healthcheck'] ?? [],
            extra: array_diff_key($data, array_flip([
                'image', 'build', 'ports', 'volumes', 'environment',
                'depends_on', 'networks', 'labels', 'command', 'restart', 'healthcheck',
            ]))
        );
    }
}
