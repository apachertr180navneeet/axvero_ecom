<?php
namespace App\Exports;

use App\Models\AgentJoin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgentJoinExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = AgentJoin::query();

        if (!empty($this->filters['email']))
            $query->where('email', 'like', '%' . $this->filters['email'] . '%');

        if (!empty($this->filters['name']))
            $query->where('name', 'like', '%' . $this->filters['name'] . '%');

        if (!empty($this->filters['status']))
            $query->where('payment_status', $this->filters['status']);

        if (!empty($this->filters['date_from']))
            $query->whereDate('created_at', '>=', $this->filters['date_from']);

        if (!empty($this->filters['date_to']))
            $query->whereDate('created_at', '<=', $this->filters['date_to']);

        return $query->latest()->get([
            'name', 'email', 'mobile', 'manager_name',
            'manager_company_email', 'referred_person_name',
            'referred_person_mobile', 'amount', 'payment_status', 'created_at'
        ]);
    }

    public function headings(): array
    {
        return [
            'Name', 'Email', 'Mobile', 'Manager',
            'Manager Email', 'Referred By', 'Referred Mobile',
            'Amount', 'Payment Status', 'Date',
        ];
    }
}