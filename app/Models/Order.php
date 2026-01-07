<?php
// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'invoice_number', 'status', 'total_price'];

    /**
     * Get the user that owns this order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for this order
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if order is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if order is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if order is cancelled
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
