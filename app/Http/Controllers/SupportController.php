<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminResponseForSupport;
use App\Http\Requests\ClientRequestForSupport;
use App\Models\Support;
use Illuminate\Http\JsonResponse;

class SupportController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $supportItems = Support::query()->get();
        return successResponse([
            'supportItems' => $supportItems
        ]);
    }

    /**
     * @param ClientRequestForSupport $request
     * @return JsonResponse
     */
    public function clientRequest(ClientRequestForSupport $request): JsonResponse
    {
        $data = $request->validated();
        Support::query()->create($data);
        return successResponse();
    }

    /**
     * @param AdminResponseForSupport $request
     * @return JsonResponse
     */
    public function adminResponse(AdminResponseForSupport $request): JsonResponse
    {
        $data = $request->validated();
        $support = Support::query()->findOrFail($data['support_id']);
        $support->update(['status' => $data['status']]);

        // TODO: Send an email to client with the $data['answer'] content.

        return successResponse();
    }
}
