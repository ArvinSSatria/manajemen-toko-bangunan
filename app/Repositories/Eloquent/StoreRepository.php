<?php

namespace App\Repositories\Eloquent;

use App\Models\Store;
use Illuminate\Pagination\LengthAwarePaginator;

class StoreRepository extends BaseRepository
{
    public function __construct(Store $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->withCount('users');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('address', 'like', "%{$filters['search']}%")
                  ->orWhere('phone', 'like', "%{$filters['search']}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function getActiveStores()
    {
        return $this->model->where('is_active', true)->get();
    }
}
