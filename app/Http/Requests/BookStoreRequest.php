<?php

namespace App\Http\Requests;

class BookStoreRequest extends CustomFormRequest
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
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'genre_id' => 'required|exists:genres,id',
            'pages' => 'required|integer|min:1',
            'publication_year' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
//            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Max size of 2MB
            'available_quantity' => 'required|integer|min:0',
        ];
    }


    public function messages()
    {
        return [
            'title.required' => 'The book title is required.',
            'author.required' => 'The author name is required.',
            'genre_id.exist' => 'The genre is not valid.',
            'genre_id.required' => 'The genre is required.',
            'pages.required' => 'The number of pages is required.',
            'pages.integer' => 'The number of pages must be an integer.',
            'publication_year.required' => 'The publication year is required.',
            'publication_year.digits' => 'The publication year must be a 4-digit year.',
            'publication_year.max' => 'The publication year cannot be in the future.',
//            'cover_image.image' => 'The cover image must be a valid image file.',
//            'cover_image.mimes' => 'The cover image must be a file of type: jpg, jpeg, png, gif.',
//            'cover_image.max' => 'The cover image may not be greater than 2MB.',
            'available_quantity.required' => 'The number of available copies is required.',
        ];
    }


}
