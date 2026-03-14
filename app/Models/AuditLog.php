<?php
// app/Models/AuditLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $primaryKey = 'log_id';
    
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
        'old_data',
        'new_data',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Scope for filtering by action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for filtering by module
     */
    public function scopeModule($query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}