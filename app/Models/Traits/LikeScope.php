<?php

namespace App\Models\Traits;

trait LikeScope
{
    /**
     * @param   \Illuminate\Database\Eloquent\Builder $query
     * @param     $column
     * @param     $value
     * @param     $side
     * @param     $isNotLike
     * @param     $isAnd
     * @return    \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLike($query, $column, $value, $side = 'both', $isNotLike = false, $isAnd = true)
    {
        $operator = $isNotLike ? 'not like' : 'like';

        $value = $this->escapeLikeString($value);

        switch ($side) {
            case 'none':

                break;
            case 'before':
            case 'left':
                $value = "%{$value}";

                break;
            case 'after':
            case 'right':
                $value = "{$value}%";

                break;
            case 'both':
            case 'all':
            default:
                $value = "%{$value}%";

                break;
        }

        return $isAnd ? $query->where($column, $operator, $value) : $query->orWhere($column, $operator, $value);
    }

    public function escapeLikeString($string)
    {
        $like_escape_char = '\\';

        return str_replace(
            [
                $like_escape_char,
                '%',
                '_'],
            [
                $like_escape_char . $like_escape_char,
                $like_escape_char . '%',
                $like_escape_char . '_',
            ],
            $string
        );
    }

    public function scopeOrLike($query, $column, $value, $side = 'both', $isNotLike = false)
    {
        return $query->like($column, $value, $side, $isNotLike, false);
    }

    public function scopeNotLike($query, $column, $value, $side = 'both', $isAnd = true)
    {
        return $query->like($column, $value, $side, true, $isAnd);
    }

    public function scopeOrNotLike($query, $column, $value, $side = 'both')
    {
        return $query->like($column, $value, $side, true, false);
    }
}
