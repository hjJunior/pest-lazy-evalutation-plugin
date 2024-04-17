<?php

declare(strict_types=1);

namespace Pest\Let\Concern;

use Closure;

trait HasLetVariables
{
    /**
     * @var array<string, array{'resolved': bool, 'value': mixed, 'resolver': Closure}>
     */
    private array $variables = [];

    public function let(string $key, Closure $resolver): self
    {
        $this->setVariable($key, $resolver);

        return $this;
    }

    public function get(string $key): mixed
    {
        return $this->getVariableValue($key);
    }

    private function getVariableValue(string $key): mixed
    {
        if (! array_key_exists($key, $this->variables)) {
            /** @phpstan-ignore-next-line */
            throw new \Exception("Attempt to read $key, when was not set");
        }

        if ($this->variables[$key]['resolved']) {
            return $this->variables[$key]['value'];
        }

        return $this->resolveVariable($key);
    }

    private function setVariable(string $key, Closure $resolver): void
    {
        $this->variables[$key] = [
            'resolved' => false,
            'resolver' => $resolver,
            'value' => null,
        ];
    }

    private function resolveVariable(string $key): mixed
    {
        $value = $this->variables[$key]['resolver']();

        $this->variables[$key]['value'] = $value;
        $this->variables[$key]['resolved'] = true;

        return $value;
    }
}
