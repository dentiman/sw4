<?php


namespace App\ApiPlatform\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Common\Filter\NumericFilterTrait;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Exception\InvalidArgumentException;
use Doctrine\DBAL\Types\Type as DBALType;
use Doctrine\ORM\QueryBuilder;


class LevelFilter extends RangeFilter
{

    /**
     * {@inheritdoc}
     */
    protected function filterProperty(string $property, $values, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {

        if (
            !\is_array($values)
        ) {
            return;
        }

        $values = $this->normalizeValues($values, $property);
        if (null === $values) {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];
        $field = $property;

        if ($this->isPropertyNested($property, $resourceClass)) {
            [$alias, $field] = $this->addJoinsForNestedProperty($property, $alias, $queryBuilder, $queryNameGenerator, $resourceClass);
        }

        foreach ($values as $operator => $value) {
            $this->addWhere(
                $queryBuilder,
                $queryNameGenerator,
                $alias,
                $field,
                $operator,
                $value
            );
        }
    }

    private function normalizeValues(array $values, string $property): ?array
    {
        $operators = [self::PARAMETER_BETWEEN, self::PARAMETER_GREATER_THAN, self::PARAMETER_GREATER_THAN_OR_EQUAL, self::PARAMETER_LESS_THAN, self::PARAMETER_LESS_THAN_OR_EQUAL];

        foreach ($values as $operator => $value) {
            if (!\in_array($operator, $operators, true)) {
                unset($values[$operator]);
            }
        }

        if (empty($values)) {
            $this->getLogger()->notice('Invalid filter ignored', [
                'exception' => new InvalidArgumentException(sprintf('At least one valid operator ("%s") is required for "%s" property', implode('", "', $operators), $property)),
            ]);

            return null;
        }

        return $values;
    }


    /**
     * {@inheritdoc}
     */
    public function getDescription(string $resourceClass): array
    {
        $description = [];

        $properties = $this->getProperties();
//        if (null === $properties) {
//            $properties = array_fill_keys($this->getClassMetadata($resourceClass)->getFieldNames(), null);
//        }

        foreach ($properties as $property => $unused) {
//            if (!$this->isPropertyMapped($property, $resourceClass)) {
//                continue;
//            }

            $description += $this->getFilterDescription($property, self::PARAMETER_BETWEEN);
            $description += $this->getFilterDescription($property, self::PARAMETER_GREATER_THAN);
            $description += $this->getFilterDescription($property, self::PARAMETER_GREATER_THAN_OR_EQUAL);
            $description += $this->getFilterDescription($property, self::PARAMETER_LESS_THAN);
            $description += $this->getFilterDescription($property, self::PARAMETER_LESS_THAN_OR_EQUAL);
        }

        return $description;
    }
}
