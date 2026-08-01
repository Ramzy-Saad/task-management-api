<?php

    namespace App\Http\Requests\Auth;

    use Illuminate\Foundation\Http\FormRequest;
    use OpenApi\Attributes as OA;

    #[OA\Schema(
        schema: "RegisterRequest",
        title: "Register Request Body",
        required: ["name", "email", "password", "password_confirmation"],
        properties: [
            new OA\Property(property: "name", type: "string", example: "John Doe"),
            new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
            new OA\Property(property: "password", type: "string", format: "password", example: "password123"),
            new OA\Property(property: "password_confirmation", type: "string", format: "password", example: "password123"),
        ]
    )]
    class RegisterRequest extends FormRequest
    {
        public function authorize(): bool
        {
            return true;
        }

        public function rules(): array
        {
            return [
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ];
        }
    }