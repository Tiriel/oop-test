<?php

namespace App\Db\Query\Core;

use App\Db\Connection;
use App\Db\Model\ModelInterface;
use App\Db\Model\Post;

abstract class ClassBoundQuery extends TableBoundQuery
{
    public function find(int $id): object|null
    {
        return $this->fetchQuery(['id', '=', $id])[0] ?? null;
    }

    public function findBy(array $findBy = [], array $orderBy = [], int $limit = 0, int $offset = 0): array
    {
        return $this->fetchQuery($findBy, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $findBy, array $orderBy = []): object|null
    {
        return $this->fetchQuery($findBy, $orderBy + ['DESC' => 'id'], 1)[0] ?? null;
    }

    public function findAll(): array
    {
        return $this->fetchQuery();
    }

    public function save(ModelInterface $object): object
    {
        if (get_class($object) !== $this->fqcn) {
            throw new \InvalidArgumentException(sprintf("%s::%s can only save objects with class %s.", static::class, __METHOD__, $this->fqcn));
        }
        $values = $this->extractValues($object);

        if (null !== $object->getId()) {

            $this->executeQuery($values, ['id', '=', $object->getId()]);

            return $object;
        }

        unset($values['id']);

        $this->executeQuery($values);

        $id = $this->db->lastInsertId($this->shortName);
        (new \ReflectionClass($object))?->getProperty('id')?->setValue($object, $id);

        return $object;
    }

    public function remove(ModelInterface $object): bool
    {
        return $this->delete($this->shortName, ['id', '=', $object->getId()]);
    }

    public static function createClassQuery(Connection $db, string $model): Query
    {
        $modelClass = "App\\Db\\Model\\$model";
        if (!\class_exists($modelClass)) {
            $model = 'Post';
            $modelClass = Post::class;
        }
        $queryClass = "App\\Db\\Query\\{$model}Query";

        return new $queryClass($db, $modelClass);
    }

    protected function extractValues(ModelInterface $object): array
    {
        $values = [];
        foreach ((array)$object as $key => $value) {
            [, , $property] = explode("\0", $key);
            $values[$property] = $value;
        }

        return $values;
    }
}
