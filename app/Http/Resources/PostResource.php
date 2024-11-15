<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // public function toArray(Request $request): array
    // {
    //     return parent::toArray($request);
    // }

    private $message;
    private $success;

    public function __construct($success,$message,$resource)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->success = $success;
    }

    public function toArray($request)
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->resource
        ];
    }
}
