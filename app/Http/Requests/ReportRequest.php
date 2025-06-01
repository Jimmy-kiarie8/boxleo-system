<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Or implement your authorization logic
    }

    public function rules()
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'agent_id' => 'sometimes|array',
            'agent_id.*' => 'integer|exists:users,id',
            'seller_id' => 'sometimes|array',
            'seller_id.*' => 'integer|exists:sellers,id',
        ];
    }
}