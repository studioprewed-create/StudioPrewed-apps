<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrderHelper
{
    private static function query(
        string $modelClass,
        array $scope = []
    ): Builder {
        $query = $modelClass::query();

        foreach ($scope as $field => $value) {
            $query->where($field, $value);
        }

        return $query;
    }

    public static function insert(
        string $modelClass,
        $requestedOrder = null,
        array $scope = []
    ): int {
        self::query($modelClass, $scope)
            ->lockForUpdate()
            ->get();

        $maxOrder = (int) (
            self::query($modelClass, $scope)
                ->max('order') ?? 0
        );

        if (!is_numeric($requestedOrder)) {
            return $maxOrder + 1;
        }

        $position = max(
            1,
            min(
                (int) $requestedOrder,
                $maxOrder + 1
            )
        );

        self::query($modelClass, $scope)
            ->whereNotNull('order')
            ->where('order', '>=', $position)
            ->increment('order');

        return $position;
    }

    public static function move(
        Model $model,
        $requestedOrder,
        array $scope = []
    ): int {
        if (!is_numeric($requestedOrder)) {
            return (int) $model->order;
        }

        $modelClass = get_class($model);
        $keyName = $model->getKeyName();

        self::query($modelClass, $scope)
            ->lockForUpdate()
            ->get();

        $oldOrder = (int) $model->order;

        $maxOrder = (int) (
            self::query($modelClass, $scope)
                ->max('order') ?? 1
        );

        $newOrder = max(
            1,
            min((int) $requestedOrder, $maxOrder)
        );

        if ($newOrder === $oldOrder) {
            return $oldOrder;
        }

        $query = self::query($modelClass, $scope)
            ->where($keyName, '!=', $model->getKey());

        if ($newOrder < $oldOrder) {
            $query
                ->whereBetween(
                    'order',
                    [$newOrder, $oldOrder - 1]
                )
                ->increment('order');
        } else {
            $query
                ->whereBetween(
                    'order',
                    [$oldOrder + 1, $newOrder]
                )
                ->decrement('order');
        }

        return $newOrder;
    }

    public static function remove(
        string $modelClass,
        int $removedOrder,
        array $scope = []
    ): void {
        self::query($modelClass, $scope)
            ->lockForUpdate()
            ->get();

        self::query($modelClass, $scope)
            ->where('order', '>', $removedOrder)
            ->decrement('order');
    }
}