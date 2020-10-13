<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;

class Post extends Model {

    use HasFactory;
    use Sluggable;

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;

    protected $fillable = [
        'title',
        'content'
    ];

    public function category() {
        return $this->hasOne(Category::class);
    }

    public function author() {
        return $this->hasOne(User::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id', 'tag_id');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable() {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add($fields) {
        $post = new static;
        $post->fill($fields);
        $post->user_id = 1;
        $post->save();

        return $post;
    }

    public function edit($fields) {
        $this->fill($fields);
        $this->save();
    }

    public function remove() {
        Storage::delete('uploads/' . $this->image);
        $this->delete();
    }

    public function uploadImage($image) {
        if ($image == null) {
            return;
        }

        Storage::delete('uploads/' . $this->image);
        $filename = str_random(10) . '.' . $image->extension();
        $image->saveAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function getImage() {
        if ($this->image == null) {
            return '/img/no-image.png';
        }
        return '/uploads' . $this->image;
    }

    public function setCategory($id) {
        if ($id == null) {
            return;
        }

        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids) {
        if ($ids == null) {
            return;
        }

        $this->tags()->sync($ids);
    }

    public function setDraft() {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    public function setPublic() {
        $this->status = Post::IS_PUBLIC;
        $this->save();
    }

    public function toogleStatus($value) {
        if ($value == null) {
            return $this->setDraft();
        }
        return $this->setPublic();
    }

    public function setFeatured() {
        $this->is_featured = 1;
        $this->save();
    }

    public function setStandart() {
        $this->is_featured = 0;
        $this->save();
    }

    public function toogleFeatured($value) {
        if ($value == null) {
            return $this->setStandart();
        }
        return $this->setFeatured();
    }
}
