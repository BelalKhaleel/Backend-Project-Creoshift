<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return User::all();
    // }

    use Exportable;
    
    public function query()
    {
        $users = User::query()->with(['post', 'comment']);
        return $users;
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->email_verified_at ? $user->email_verified_at->format('Y-m-d') : "NULL",
            $user->created_at->format('Y-m-d'),
            $user->updated_at->format('Y-m-d'),
            $user->posts->id,
            $user->posts->title,
            $user->posts->content,
            $user->comments->id,
            $user->comments->content,
        ];
    }

    public function headings(): array
    {
        return 
        [
         "id",
         "name",
         "email", 
         "email_verified_at", 
         "created_at", 
         "updated_at",
         "post_id",
         "post_title",
         "post_content",
         "comment_id",
         "comment_content",
        ];
    }
}
