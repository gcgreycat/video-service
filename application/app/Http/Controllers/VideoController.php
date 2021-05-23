<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginableRequest;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\JsonResponse;

class VideoController extends Controller
{
    public function list(PaginableRequest $request): JsonResponse
    {
        $perPage = $request->perPage ?? 20;

        /** @var User $user */
        $user = auth()->user();

        $pagination = Video::byUser($user)->paginate($perPage);
        $videos = array_map(function (Video $video) {
            return [
                'id' => $video->id,
                'title' => $video->title,
            ];
        }, $pagination->items());

        return response()->json(['total' => $pagination->total(), 'videos' => $videos]);
    }
}
