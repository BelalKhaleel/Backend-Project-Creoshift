<?php

namespace App\Exports;

use App\Models\Post;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PostsExport implements FromQuery, ShouldAutoSize, WithMapping, WithHeadings
{
    use Exportable;

    public function query()
    {
        $posts = QueryBuilder::for(Post::class)
            ->with(['user', 'comments'])
            ->allowedFilters(['title', 'content', 'user_id', AllowedFilter::exact('id')]);

        return $posts;
    }

    public function map($post): array
    {
        return [
            $post->id,
            $post->title,
            $post->content,
            Date::dateTimeToExcel($post->created_at),
            $post->user->name,
            $post->comments->pluck('content')->implode(', '),
        ];
    }

    public function headings(): array
    {
        return [
            "id",
            "title",
            "post",
            "created_at",
            "user",
            "comment(s)",
        ];
    }
}
