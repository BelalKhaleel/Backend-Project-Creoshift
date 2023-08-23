<?php

namespace App\Imports;

use App\Models\Post;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PostsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // $user = User::find($row['user_id']);

        // if (!$user) {
        //     // Handle the case where the user does not exist
        //     // For example, you might want to skip this row
        //     return null;
        // }
        return new Post([
            'user_id'       => $row['user_id'],
            'title'    => $row['title'],
            'content'     => $row['post'], 
        ]);
    }
}
