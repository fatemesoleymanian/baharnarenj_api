<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['index', 'show']);
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $sliders = Slider::query()->get();
        return successResponse([
            'sliders' => $sliders
        ]);
    }

    /**
     * @param SliderRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store(SliderRequest $request): JsonResponse
    {
        $data = filterData($request->validated());
        $data['image'] = handleFile('/sliders', $data['image']);
        Slider::query()->updateOrCreate(['title' => $data['title']], $data);
        return successResponse();
    }

    /**
     * @param Slider $slider
     * @return JsonResponse
     */
    public function show(Slider $slider): JsonResponse
    {
        return successResponse([
            'slider' => $slider
        ]);
    }

    /**
     * @param SliderRequest $request
     * @param Slider $slider
     * @return JsonResponse
     * @throws CustomException
     */
    public function update(SliderRequest $request, Slider $slider): JsonResponse
    {
        $data = filterData($request->validated());
        if (exists($data['image'])) {
            $data['image'] = handleFile('/sliders', $data['image']);
        }
        $slider->update($data);
        return successResponse();
    }

    /**
     * @param Slider $slider
     * @return JsonResponse
     */
    public function destroy(Slider $slider): JsonResponse
    {
        $slider->delete();
        return successResponse();
    }
}
