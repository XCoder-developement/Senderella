<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        $user = auth()->user();

        $user_answer = $user->informations?->where('requirment_id',$this->id)->first();
       
       return [
        'id'=>$this->id,
        'question'=>$this->title ?? '',
        'answer'=>$user_answer->answer ?? '',
        // 'type'=>$this->answer_type,
        // 'items'=>TitleResource::collection($this->user_questions),
       ];
    }
}
