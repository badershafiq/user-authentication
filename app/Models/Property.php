<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Property extends Model {
	use HasFactory;
	
	/**
	 * @var bool
	 */
	public $timestamps = true;
	
	/**
	 * @var string[]
	 */
	protected $fillable = ['name', 'image_path', 'description'];
	
	/**
	 * @var string[]
	 */
	protected $appends = ['file_path'];
	
	/**
	 * @return string
	 */
	protected function getFilePathAttribute () {
		return config('app.url') . '/storage' . $this->image_path;
	}
}
