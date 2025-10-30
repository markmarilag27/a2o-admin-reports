<?php

declare(strict_types=1);

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class BaseReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'markets' => ['sometimes', 'array'],
            'markets.*' => ['integer', 'exists:markets,id'],

            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'start_date' => $this->input('start_date') ?? now()->subDays(30)->toDateString(),
            'end_date' => $this->input('end_date') ?? now()->toDateString(),
        ]);
    }

    public function allowedMarketIds(): array
    {
        $user = $this->user() ?? $this->user('sanctum');

        if (! $user || $user->isAdmin()) {
            return (array) $this->input('markets', []);
        }

        $allowedIds = $user->markets()->pluck('markets.id')->toArray();
        $requested = (array) $this->input('markets', []);

        return $requested
            ? array_values(array_intersect($allowedIds, $requested))
            : $allowedIds;
    }

    public function filters(): array
    {
        return [
            'markets' => $this->allowedMarketIds(),
            'start_date' => $this->input('start_date'),
            'end_date' => $this->input('end_date'),
        ];
    }
}
