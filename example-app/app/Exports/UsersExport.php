<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Comment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function query()
    {
        $users = User::query()->with(['posts', 'comments']);
        return $users;
    }

    public function headings(): array
    {
        return
        [
         "id",
         "name",
         "email",
         "post title",
         "post",
         "comment",
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->posts->pluck('title')->implode(', '),
            $user->posts->pluck('content')->implode(', '),
            $user->comments->pluck('content')->implode(', '),
        ];
    }


}