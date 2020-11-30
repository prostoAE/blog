<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model {

    use HasFactory;
    use Sluggable;

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;

    protected $fillable = [
        'title',
        'content',
        'date'
    ];

    public function category() {
        return $this->belongsTo( Category::class );
    }

    public function author() {
        return $this->belongsTo( User::class, 'user_id' );
    }

    public function tags() {
        return $this->belongsToMany( Tag::class, 'posts_tags', 'post_id', 'tag_id' );
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

    public static function add( $fields ) {
        $post = new static;
        $post->fill( $fields );
        $post->user_id = 1;
        $post->save();

        return $post;
    }

    public function edit( $fields ) {
        $this->fill( $fields );
        $this->save();
    }

    public function remove() {
        $this->removeImage();
        $this->delete();
    }

    public function removeImage() {
        if ($this->image != null) {
            Storage::delete( 'uploads/' . $this->image );
        }
    }

    public function uploadImage( $image ) {
        if ($image == null) {
            return;
        }

        $this->removeImage();
        $filename = Str::random( 10 ) . '.' . $image->extension();
        $image->storeAs( 'uploads', $filename );
        $this->image = $filename;
        $this->save();
    }

    public function getImage() {
        if ($this->image == null) {
            return '/img/no-image.png';
        }
        return '/uploads/' . $this->image;
    }

    public function setCategory( $id ) {
        if ($id == null) {
            return;
        }

        $this->category_id = $id;
        $this->save();
    }

    public function setTags( $ids ) {
        if ($ids == null) {
            return;
        }

        $this->tags()->sync( $ids );
    }

    public function setDraft() {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    public function setPublic() {
        $this->status = Post::IS_PUBLIC;
        $this->save();
    }

    public function toogleStatus( $value ) {
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

    public function toogleFeatured( $value ) {
        if ($value == null) {
            return $this->setStandart();
        }
        return $this->setFeatured();
    }

    public function setDateAttribute( $value ) {
        $date = Carbon::createFromFormat( 'd/m/y', $value )->format( 'Y-m-d' );
        $this->attributes['date'] = $date;
    }

    public function getDateAttribute( $value ) {
        $date = Carbon::createFromFormat( 'Y-m-d', $value )->format( 'd/m/y' );
        return $date;
    }

    public function getCategoryTitle(  ) {
        return ($this->category != null) ? $this->category->title : 'Нет категории';
    }

    public function getTagsTitles(  ) {
        if ($this->tags->isNotEmpty()) {
            return implode(',', $this->tags->pluck('title')->all());
        }

        return 'Нет тегов';
    }
}
