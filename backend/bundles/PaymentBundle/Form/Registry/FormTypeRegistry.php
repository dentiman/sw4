<?php


namespace Dentiman\PaymentBundle\Form\Registry;

final class FormTypeRegistry implements FormTypeRegistryInterface
{
    /** @var array|string[] */
    private $formTypes = [];

    /**
     * {@inheritdoc}
     */
    public function add(string $identifier, string $typeIdentifier, string $formType): void
    {
        $this->formTypes[$identifier][$typeIdentifier] = $formType;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $identifier, string $typeIdentifier): ?string
    {
        if (!$this->has($identifier, $typeIdentifier)) {
            return null;
        }

        return $this->formTypes[$identifier][$typeIdentifier];
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $identifier, string $typeIdentifier): bool
    {
        return isset($this->formTypes[$identifier][$typeIdentifier]);
    }
}
