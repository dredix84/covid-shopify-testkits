<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\Feedback;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Excel;

class FeedbackAggregateExport implements FromArray, Responsable
{
    use Exportable;

    private $fileName = 'invoices.xlsx';

    private $writerType = Excel::XLSX;

    private $startDate;
    private $endDate;

    private $locationNames = [];

    public function __construct()
    {
        $request         = request();
        $filters         = json_decode($request->filters);
        $this->startDate = $filters->date_range[0];
        $this->endDate   = $filters->date_range[1];
        $this->fileName  = sprintf('FeedbackAggregateReport_%s-%s_%s.xlsx', $this->startDate, $this->endDate, time());
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $feedbacks = Feedback::whereBetween(
            'created_at', [
                Carbon::createFromFormat('Y-m-d', $this->startDate),
                Carbon::createFromFormat('Y-m-d', $this->endDate)
            ]
        )->get();

        $reportData = [
            $this->headings()
        ];

        foreach ($feedbacks as $feedback) {
            $customerId = $feedback->customer_id;
            if (!isset($reportData[$customerId])) {
                $customer   = Customer::select([
                    'last_order', 'last_order_id', 'email', 'shopify_id'
                ])->find($customerId);
                $postalCode = $customer->last_pickup_postal_code;
                if (isset($this->locationNames[$postalCode])) {
                    $pickupLocation = $this->locationNames[$postalCode];
                } else {
                    $pickupLocation = $customer->lastPickLocationName;
                    if ($pickupLocation !== null) {
                        $this->locationNames[$postalCode] = $pickupLocation;
                    } else {
                        $pickupLocation = 'Unknown';
                    }
                }
            }

            $reportData[$customerId] = [
                'id'          => sprintf('%s - %s', $pickupLocation, $customerId),
                'postal_code' => $postalCode,
                'test_type'   => 'Rapid Test',
                'used'        => 0,
                'invalid'     => 0,
                'positive'    => 0,
                'negative'    => 0,
            ];

            $reportData[$customerId]['used']     += $feedback->antigen_tests_administered;
            $reportData[$customerId]['invalid']  += $feedback->inconclusive;
            $reportData[$customerId]['positive'] += $feedback->presumptive_positive;
            $reportData[$customerId]['negative'] += $feedback->presumptive_negative;
        }

        return $reportData;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Site Postal Code',
            'Test Type',
            'Tests Used',
            'Invalid',
            'Positive',
            'Negative'
        ];
    }

    public function title(): string
    {
        return sprintf('%s - %s', $this->startDate, $this->endDate);
    }
}
