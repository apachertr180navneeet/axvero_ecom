<?php
namespace App\Imports;

use App\Models\AgentJoin;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AgentJoinImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
{
    if (!isset($row['email']) || empty(trim($row['email']))) {
        return null;
    }

    $email = strtolower(trim($row['email']));

    AgentJoin::whereRaw('LOWER(email) = ?', [$email])
        ->update([
            'payment_status' => 'success'
        ]);

    return null;
}
}