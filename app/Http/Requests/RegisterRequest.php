<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends CustomFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:11',
            'address' => 'nullable|string|max:255',
            'role' => 'required|in:user,admin',
        ];
    }

    /**
     * Get custom error messages for the validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The username is required.',
            'name.string' => 'The username must be a valid string.',
            'name.max' => 'The username cannot exceed 255 characters.',
            'name.unique' => 'This username is already taken, please choose another one.',

            'email.required' => 'The email address is required.',
            'email.email' => 'The email address must be a valid email format.',
            'email.max' => 'The email address cannot exceed 255 characters.',
            'email.unique' => 'This email is already registered, please use a different one.',

            'password.required' => 'The password is required.',
            'password.string' => 'The password must be a valid string.',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',

            'phone_number.string' => 'The phone number must be a valid string.',
            'phone_number.max' => 'The phone number cannot exceed 15 characters.',

            'address.string' => 'The address must be a valid string.',
            'address.max' => 'The address cannot exceed 255 characters.',

            'role.required' => 'The role is required.',
            'role.in' => 'The role must be either user or admin.',
        ];
    }
}
