<?php

declare(strict_types=1);

namespace App\Http\Requests\Reports;

use Illuminate\Validation\Validator;

class ConversionFunnelReportRequest extends BaseReportRequest
{
    protected function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $markets = (array) $this->input('markets', []);
            if (count($markets) > 1) {
                $validator->errors()->add('markets', 'Only one market is allowed for this report due to performance constraints.');
            }
        });
    }
}
