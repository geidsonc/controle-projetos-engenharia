<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "name" => $this->name,
            "process_number" => $this->process_number,
            "agreement_number" => $this->agreement_number,
            "action" =>  $this->action,
            "action_value" => $this->translateAction(),
            "start_date" => $this->start_date,
            "end_date" => $this->end_date,
            "city" => $this->city,
            "resume" => $this->resume,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "users" => UserResource::collection($this->whenLoaded('users')),
            "project_status" => ProjectStatusResource::collection($this->whenLoaded('projectStatus'))
        ];
    }
}
