<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'slug', 'isi', 'file', 'file_nama', 'link', 'is_published'];

    protected $casts = ['is_published' => 'boolean'];

    // Alamat file siap pakai di halaman: $artikel->file_url
    public function getFileUrlAttribute(): ?string
    {
        return $this->file ? asset('storage/' . ltrim($this->file, '/')) : null;
    }

    // Ekstensi file (pdf, docx, dll.): $artikel->file_ext
    public function getFileExtAttribute(): ?string
    {
        return $this->file ? strtolower(pathinfo($this->file, PATHINFO_EXTENSION)) : null;
    }

    // Opsional: supaya Blade lama yang memakai $artikel->judul tetap jalan
    public function getJudulAttribute(): ?string
    {
        return $this->title;
    }

    // Nama situs dari link: $artikel->link_host (mis. "detik.com")
    public function getLinkHostAttribute(): ?string
    {
        if (!$this->link) return null;
        $host = parse_url($this->link, PHP_URL_HOST);
        return $host ? preg_replace('/^www\./', '', $host) : null;
    }
}