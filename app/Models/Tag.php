<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag',
        'created_at',
        'updated_at',
    ];

    /**
     * relations *
     */
    public function user(){
        return $this->belongsToMany(User::class)->withPivot();
    }
    //public function post(){
    //    return $this->belongsToMany(Post::class)->withPivot();
    //}

    // /************  FUNCTIONS  *********** */
    // /**
    // * @param string
    // * The function get a parameter from the View and retrieve the query from the DB
    // * return result
    // */
    // public static function search( $search ){

    //     $result = empty($search) ? static::query()
    //                             : static::query()
    //                                     ->where('title', 'like', '%'.$search.'%')
    //                                     ->orWhere('body', 'like', '%'.$search.'%')
    //                                     ->orWhere('description', 'like', '%'.$search.'%');

    //     return $result;
    //  }

}
