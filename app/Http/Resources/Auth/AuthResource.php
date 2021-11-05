<?php

namespace App\Http\Resources\Auth;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $email_verified_at
 * @property mixed expired_at
 */
class AuthResource extends JsonResource
{
    /**
     * @var string
     */
    public $token;

    public function __construct($resource, $token)
    {
        $this->token = $token;
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'token'             => $this->token,
            'token_type'        => 'Bearer',
            'expired_at'        => Carbon::now()->addHours(24)
        ];
    }

    /**
     * @param Request $request
     * @return string[]
     */
    public function with($request): array
    {
        return [
            'status' => 'success'
        ];
    }
}
