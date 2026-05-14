<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ServiceImageResource;
use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class ServiceImageController extends Controller
{
    public function index(Service $service): AnonymousResourceCollection
    {
        $this->authorize('view', $service);

        return ServiceImageResource::collection(
            $service->images()->latest()->paginate(50)
        );
    }

    public function store(Request $request, Service $service): JsonResponse
    {
        $this->authorize('update', $service);

        $validated = $request->validate([
            'image' => ['required', 'image', 'max:5120'],
        ]);

        $path = $validated['image']->store("services/{$service->id}", 'public');

        $image = $service->images()->create(['path' => $path]);

        return (new ServiceImageResource($image))
            ->response()
            ->setStatusCode(201);
    }

    public function destroy(Service $service, ServiceImage $image): JsonResponse
    {
        $this->authorize('update', $service);
        abort_unless($image->service_id === $service->id, 404);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->json(['message' => 'Imagem removida com sucesso.']);
    }
}
