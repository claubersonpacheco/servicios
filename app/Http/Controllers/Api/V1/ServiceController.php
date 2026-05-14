<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ServiceRequest;
use App\Http\Resources\Api\V1\ServiceResource;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Service::class);

        $sortBy = $request->string('sort_by', 'created_at')->toString();
        abort_unless(in_array($sortBy, ['code', 'status', 'date_start', 'created_at'], true), 422);

        $sortDirection = $request->string('sort_direction', 'desc')->toString() === 'asc' ? 'asc' : 'desc';
        $perPage = min(max((int) $request->integer('per_page', 15), 1), 100);

        $services = Service::query()
            ->with('user', 'images')
            ->visibleFor(Auth::user())
            ->when($request->filled('search'), function (Builder $query) use ($request): void {
                $term = '%'.trim($request->string('search')->toString()).'%';

                $query->where(function (Builder $builder) use ($term): void {
                    $builder
                        ->where('code', 'like', $term)
                        ->orWhere('address', 'like', $term)
                        ->orWhere('number', 'like', $term)
                        ->orWhere('city', 'like', $term)
                        ->orWhere('state', 'like', $term)
                        ->orWhere('postal', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhereHas('user', fn ($q) => $q->where('name', 'like', $term));
                });
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage);

        return ServiceResource::collection($services);
    }

    public function store(ServiceRequest $request): JsonResponse
    {
        $this->authorize('create', Service::class);

        $service = Service::query()->create($this->payload($request));

        return (new ServiceResource($service->load('user')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Service $service): ServiceResource
    {
        $this->authorize('view', $service);

        return new ServiceResource($service->load('user', 'images'));
    }

    public function update(ServiceRequest $request, Service $service): ServiceResource
    {
        $this->authorize('update', $service);

        $payload = $this->payload($request);

        if (! Auth::user()?->can('services.manage')) {
            unset($payload['user_id']);
        }

        $service->update($payload);

        return new ServiceResource($service->refresh()->load('user', 'images'));
    }

    public function destroy(Service $service): JsonResponse
    {
        $this->authorize('delete', $service);

        $service->delete();

        return response()->json(['message' => 'Servicio eliminado com sucesso.']);
    }

    private function payload(ServiceRequest $request): array
    {
        return collect($request->validated())
            ->map(fn ($value) => $value === '' ? null : $value)
            ->all();
    }
}
