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
        $page = $request->page ?? 1;
        $perPage = $request->perPage ?? 20;

        /** @var User $user */
        $user = auth()->user();
        $count = Video::countByUser($user);
        if (empty($count)) {
            return response()->json(['total' => $count, 'videos' => []]);
        }

        $videos = Video::getByUser($user, $perPage, $perPage * ($page - 1))->map(function (Video $video) {
            return [
                'id' => $video->id,
                'title' => $video->title,
            ];
        });
        return response()->json(['total' => $count, 'videos' => $videos]);
    }
}
